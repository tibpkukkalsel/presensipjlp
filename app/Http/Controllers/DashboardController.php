<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1; //1 atau Januari
        $tahunini = date("Y"); // 2023
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;

        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $presensihariini = DB::table('presensi')
            ->select('presensi.*', 'pengajuan_izin.keterangan')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('presensi.nik', $nik)
            ->where('presensi.tgl_presensi', $hariini)
            ->first();

        // Cek hari libur hari ini untuk pegawai
        $dataliburhariini = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->where('harilibur.tanggal_libur', $hariini)
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.kode_libur', 'harilibur.tanggal_libur', 'harilibur.keterangan')
            ->first();

        if (!$dataliburhariini && $presensihariini && $presensihariini->status == 'l') {
            $dataliburhariini = (object)[
                'kode_libur' => 'LIBUR',
                'tanggal_libur' => $hariini,
                'keterangan' => !empty($presensihariini->keterangan) ? $presensihariini->keterangan : 'Libur Nasional / Bersama'
            ];
        }

        // Rentang tanggal minggu yang berjalan (Senin sampai Minggu)
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $historimingguini = DB::table('presensi')
            ->select('presensi.*', 'keterangan', 'jam_kerja.*', 'doc_sid', 'nama_cuti')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->where('presensi.nik', $nik)
            ->whereBetween('presensi.tgl_presensi', [$startOfWeek, $endOfWeek])
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        // Ambil hari libur minggu ini untuk pegawai dan gabungkan ke histori
        $hariliburmingguini = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->whereBetween('harilibur.tanggal_libur', [$startOfWeek, $endOfWeek])
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.tanggal_libur', 'harilibur.keterangan')
            ->distinct()
            ->get();

        $existingDates = $historimingguini->pluck('tgl_presensi')->toArray();
        $historyList = $historimingguini->map(function ($item) use ($hariliburmingguini) {
            if ($item->status == 'l' && empty($item->keterangan)) {
                $hl = $hariliburmingguini->firstWhere('tanggal_libur', $item->tgl_presensi);
                if ($hl) {
                    $item->keterangan = $hl->keterangan;
                }
            }
            return $item;
        });

        foreach ($hariliburmingguini as $hl) {
            if (!in_array($hl->tanggal_libur, $existingDates)) {
                $historyList->push((object)[
                    'id' => null,
                    'nik' => $nik,
                    'tgl_presensi' => $hl->tanggal_libur,
                    'jam_in' => null,
                    'jam_out' => null,
                    'foto_in' => null,
                    'foto_out' => null,
                    'lokasi_in' => null,
                    'lokasi_out' => null,
                    'status' => 'l',
                    'status_in' => null,
                    'status_out' => null,
                    'kode_jam_kerja' => null,
                    'keterangan' => $hl->keterangan,
                    'nama_jam_kerja' => 'Libur Nasional',
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'doc_sid' => null,
                    'nama_cuti' => null,
                    'kode_izin' => null
                ]);
            }
        }

        $historimingguini = $historyList->sortByDesc('tgl_presensi')->values();
        $historibulanini = $historimingguini; // Kompatibilitas alias

        $rekappresensi = DB::table('presensi')
            ->selectRaw('
            SUM(IF(status="h",1,0)) as jmlhadir,
            SUM(IF(status="i",1,0)) as jmlizin,
            SUM(IF(status="s",1,0)) as jmlsakit,
            SUM(IF(status="c",1,0)) as jmlcuti,
            SUM(IF(status="h" AND ((jam_in IS NOT NULL AND jam_in != "" AND jam_in > jam_masuk) OR jam_in IS NULL OR jam_in = ""), 1, 0)) as jmlterlambat
            ')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();

        $totalLiburBulanIni = count($hariliburmingguini);

        $leaderboard = DB::table('presensi')
            ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $cabang = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $departemen = DB::table('seksi')->where('kode_dept', $kode_dept)->first();

        return view('dashboard.dashboard', compact('presensihariini', 'dataliburhariini', 'historimingguini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'totalLiburBulanIni', 'leaderboard', 'cabang', 'departemen', 'startOfWeek', 'endOfWeek'));
    }

    public function dashboardadmin()
    {
        $hariini = date("Y-m-d");
        $user = Auth::guard('user')->user();

        // Query total pegawai
        $queryPegawai = DB::table('pegawai');

        // Query rekap status presensi hari ini
        $queryRekap = DB::table('presensi')
            ->selectRaw('
                SUM(IF(status="h", 1, 0)) as jmlhadir,
                SUM(IF(status="i", 1, 0)) as jmlizin,
                SUM(IF(status="s", 1, 0)) as jmlsakit,
                SUM(IF(status="c", 1, 0)) as jmlcuti,
                SUM(IF(status="h" AND ((jam_in IS NOT NULL AND jam_in != "" AND jam_in > jam_masuk) OR jam_in IS NULL OR jam_in = ""), 1, 0)) as jmlterlambat
            ')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
            ->where('tgl_presensi', $hariini);

        // Query daftar presensi pegawai hari ini
        $queryPresensi = DB::table('presensi')
            ->select(
                'presensi.*',
                'pegawai.nama_lengkap',
                'pegawai.jabatan',
                'pegawai.foto as foto_pegawai',
                'seksi.nama_dept',
                'cabang.nama_cabang',
                'jam_kerja.nama_jam_kerja',
                'jam_kerja.jam_masuk as jam_masuk_jadwal',
                'jam_kerja.jam_pulang as jam_pulang_jadwal',
                'pengajuan_izin.keterangan as keterangan_izin'
            )
            ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
            ->leftJoin('seksi', 'pegawai.kode_dept', '=', 'seksi.kode_dept')
            ->leftJoin('cabang', 'pegawai.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('tgl_presensi', $hariini)
            ->orderBy('presensi.jam_in', 'desc')
            ->orderBy('presensi.id', 'desc');

        if ($user && $user->hasRole('admin departemen')) {
            $queryPegawai->where('pegawai.kode_cabang', $user->kode_cabang)->where('pegawai.kode_dept', $user->kode_dept);
            $queryRekap->where('pegawai.kode_cabang', $user->kode_cabang)->where('pegawai.kode_dept', $user->kode_dept);
            $queryPresensi->where('pegawai.kode_cabang', $user->kode_cabang)->where('pegawai.kode_dept', $user->kode_dept);
        }

        $totalpegawai = $queryPegawai->count();
        $rekappresensi = $queryRekap->first();
        $datapresensi = $queryPresensi->get();

        $jmlhadir = (int) ($rekappresensi->jmlhadir ?? 0);
        $jmlizin = (int) (($rekappresensi->jmlizin ?? 0) + ($rekappresensi->jmlsakit ?? 0) + ($rekappresensi->jmlcuti ?? 0));
        $jmlterlambat = (int) ($rekappresensi->jmlterlambat ?? 0);
        $jmlbelumabsen = max(0, $totalpegawai - $jmlhadir - $jmlizin);

        return view('dashboard.dashboardadmin', compact(
            'totalpegawai',
            'rekappresensi',
            'datapresensi',
            'jmlhadir',
            'jmlizin',
            'jmlterlambat',
            'jmlbelumabsen',
            'hariini'
        ));
    }
}
