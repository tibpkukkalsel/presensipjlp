<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class IzinabsenController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }


    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = $request->status ?? "i";
        $keterangan = $request->keterangan;

        if (in_array($status, ['it', 'ipc']) && empty($tgl_izin_sampai)) {
            $tgl_izin_sampai = $tgl_izin_dari;
        }

        $bulan = date("m", strtotime($tgl_izin_dari));
        $tahun = date("Y", strtotime($tgl_izin_dari));
        $thn = substr($tahun, 2, 2);
        $lastizin = DB::table('pengajuan_izin')
            ->whereRaw('MONTH(tgl_izin_dari)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_izin_dari)="' . $tahun . '"')
            ->orderBy('kode_izin', 'desc')
            ->first();
        $lastkodeizin = $lastizin != null ? $lastizin->kode_izin : "";
        $format = "IZ" . $bulan . $thn;
        $kode_izin = buatkode($lastkodeizin, $format, 3);

        $data = [
            'kode_izin' => $kode_izin,
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        // 1. Cek bentrok dengan presensi yang sudah ada
        $cekpresensi = DB::table('presensi')
            ->whereBetween('tgl_presensi', [$tgl_izin_dari, $tgl_izin_sampai])
            ->where('nik', $nik);

        if ($status == 'it') {
            // Izin Terlambat: hanya bentrok jika sudah memiliki Izin Terlambat yang disetujui
            // atau jika status kehadiran hari itu bukan hari kerja 'h' (misal cuti/sakit/libur)
            $cekpresensi = $cekpresensi->where(function ($q) {
                $q->where('status_in', 'izin_terlambat')
                    ->orWhereNotIn('status', ['h']);
            });
        } elseif ($status == 'ipc') {
            // Izin Pulang Cepat: hanya bentrok jika sudah memiliki Izin Pulang Cepat yang disetujui
            // atau jika status kehadiran hari itu bukan hari kerja 'h' (misal cuti/sakit/libur)
            $cekpresensi = $cekpresensi->where(function ($q) {
                $q->where('status_out', 'izin_pulang_cepat')
                    ->orWhereNotIn('status', ['h']);
            });
        }

        // 2. Cek bentrok dengan pengajuan izin yang sudah ada
        $cekpengajuan = DB::table('pengajuan_izin')
            ->where('nik', $nik)
            ->whereRaw('"' . $tgl_izin_dari . '" BETWEEN tgl_izin_dari AND tgl_izin_sampai');

        if ($status == 'it') {
            // Izin Terlambat (pagi) BISA berdampingan dengan Izin Pulang Cepat (sore)!
            // Hanya bentrok jika sudah ada izin selain IPC (yaitu: IT ganda, atau izin seharian i/s/c)
            $cekpengajuan = $cekpengajuan->where('status', '!=', 'ipc');
        } elseif ($status == 'ipc') {
            // Izin Pulang Cepat (sore) BISA berdampingan dengan Izin Terlambat (pagi)!
            // Hanya bentrok jika sudah ada izin selain IT (yaitu: IPC ganda, atau izin seharian i/s/c)
            $cekpengajuan = $cekpengajuan->where('status', '!=', 'it');
        }

        $datapresensi = $cekpresensi->get();

        if ($cekpresensi->count() > 0) {
            $msgPresensi = "Tidak Bisa Melakukan Pengajuan Karena sudah ada status kehadiran/izin aktif pada tanggal tersebut!";
            if ($status == 'it') {
                $msgPresensi = "Anda sudah memiliki Izin Terlambat atau izin seharian aktif pada tanggal tersebut!";
            } elseif ($status == 'ipc') {
                $msgPresensi = "Anda sudah memiliki Izin Pulang Cepat atau izin seharian aktif pada tanggal tersebut!";
            } else {
                $blacklistdate = "";
                foreach ($datapresensi as $d) {
                    $blacklistdate .= date('d-m-Y', strtotime($d->tgl_presensi)) . ", ";
                }
                $msgPresensi = "Tidak Bisa Melakukan Pengajuan Pada Tanggal " . rtrim($blacklistdate, ', ') . " Karena sudah melakukan Presensi!";
            }
            return redirect('/presensi/izin')->with(['error' => $msgPresensi]);
        } elseif ($cekpengajuan->count() > 0) {
            $msgPengajuan = "Tidak Bisa Melakukan Pengajuan Pada Tanggal Tersebut Karena Ada tanggal yang sudah digunakan Sebelumnya !";
            if ($status == 'it') {
                $msgPengajuan = "Anda sudah pernah mengajukan Izin Terlambat atau izin seharian pada tanggal tersebut!";
            } elseif ($status == 'ipc') {
                $msgPengajuan = "Anda sudah pernah mengajukan Izin Pulang Cepat atau izin seharian pada tanggal tersebut!";
            }
            return redirect('/presensi/izin')->with(['error' => $msgPengajuan]);
        } else {
            $simpan = DB::table('pengajuan_izin')->insert($data);

            if ($simpan) {
                return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
            } else {
                return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
            }
        }
    }

    public function edit($kode_izin)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->where('nik', $nik)->first();
        if (!$dataizin) {
            return redirect('/presensi/izin')->with(['error' => 'Data izin tidak ditemukan atau bukan milik Anda']);
        }
        if (isset($dataizin->status_approved) && $dataizin->status_approved == 1) {
            return redirect('/presensi/izin')->with(['error' => 'Izin yang sudah disetujui tidak dapat diedit']);
        }
        return view('izin.edit', compact('dataizin'));
    }

    public function update($kode_izin, Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->where('nik', $nik)->first();
        if (!$cekdataizin) {
            return redirect('/presensi/izin')->with(['error' => 'Data izin tidak ditemukan atau bukan milik Anda']);
        }
        if (isset($cekdataizin->status_approved) && $cekdataizin->status_approved == 1) {
            return redirect('/presensi/izin')->with(['error' => 'Izin yang sudah disetujui tidak dapat diedit']);
        }

        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $keterangan = $request->keterangan;

        try {
            $data = [
                'tgl_izin_dari' => $tgl_izin_dari,
                'tgl_izin_sampai' => $tgl_izin_sampai,
                'keterangan' => $keterangan
            ];

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->where('nik', $nik)->update($data);
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }
}
