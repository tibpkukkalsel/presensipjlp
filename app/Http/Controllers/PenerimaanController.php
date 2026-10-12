<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pengajuanizin;
use App\Models\User;
use App\Models\Dapartemen;
use App\Models\Penerimaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PenerimaanController extends Controller
{
    public function create()
    {
        return view('penerimaan.create');
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $tgl_penerimaan = $request->tgl_penerimaan;
        $r2 = $request->r2;
        $r4 = $request->r4;
        $notice_keluar = $request->notice_keluar;
        $status = "ada";
        $status_approved = "0";

        $bulan = date("m", strtotime($tgl_penerimaan));
        $tahun = date("Y", strtotime($tgl_penerimaan));
        // dd($tahun);
        $thn = substr($tahun, 2, 2);
        $lastpenerimaan = DB::table('penerimaan')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('kode_penerimaan', 'desc')
            ->first();
        $lastkodepenerimaan = $lastpenerimaan != null ? $lastpenerimaan->kode_penerimaan : "";
        $format = "PR" . $bulan . $thn;
        $kode_penerimaan = buatkode($lastkodepenerimaan, $format, 3);

        //dd($kode_penerimaan);

        //kalkulator jumlah
        $r2 = $request->input('r2');
        $r4 = $request->input ('r4');
        $result = 0;
        $result = $r2 + $r4;
        //;
        $data = [
            'kode_penerimaan' => $kode_penerimaan,
            'nik' => $nik,
            'tgl_penerimaan' => $tgl_penerimaan,
            'kode_cabang' => $kode_cabang,
            'status' => $status,
            'status_approved' => $status_approved,
            'notice_keluar' => $notice_keluar,
            'r2' => $r2,
            'r4' => $r4,
            'jumlah' => $result
        ];
            $simpan = DB::table('penerimaan')->insert($data);

            if ($simpan) {
                return redirect('/presensi/penerimaan')->with(['success' => 'Data Berhasil Disimpan']);
            } else {
                return redirect('/presensi/penerimaan')->with(['error' => 'Data Gagal Disimpan']);
            }
    }

    public function deletepenerimaan($kode_penerimaan)
    {
        $cekdatapenerimaan = DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->first();
        $doc_sid = $cekdatapenerimaan->doc_sid;

        try {
            DB::table('pengajuan_penerimaan')->where('kode_penerimaan', $kode_penerimaan)->delete();
            // dd($doc_sid);
            if ($doc_sid != null) {

                Storage::delete('/public/uploads/sid/' . $doc_sid);
            }
            return redirect('/presensi/penerimaan')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/penerimaan')->with(['error' => 'Data Gagal Dihapus']);
            //throw $th;
        }
    }

    public function edit($kode_penerimaan)
    {
        $datapenerimaan = DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->first();
        return view('penerimaan.edit', compact('datapenerimaan'));
    }

    public function update($kode_penerimaan, Request $request)
    {
        $tgl_penerimaan = $request->tgl_penerimaan;
        $r2 = $request->r2;
        $r4 = $request->r4;
        $notice_keluar = $request->notice_keluar;
        //hitung jumlah penerimaan
        $r2 = $request->input('r2');
        $r4 = $request->input ('r4');
        $result = 0;
        $result = $r2 + $r4;

        try {
            //code...
            $data = [
                'tgl_penerimaan' => $tgl_penerimaan,
                'notice_keluar' => $notice_keluar,
                'r2' => $r2,
                'r4' => $r4,
                'jumlah' => $result
            ];

            DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->update($data);
            return redirect('/presensi/penerimaan')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return redirect('/presensi/penerimaan')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    //Halaman Admin
    public function realisasi(Request $request)
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $query = Penerimaan::query();
        $query->select('penerimaan.*', 'nama_cabang', 'nama_lengkap');
        $query->join('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('karyawan', 'penerimaan.nik', '=', 'karyawan.nik');
        $query->orderBy('tgl_penerimaan');
        if (!empty($request->penerimaan)) {
            $query->where('penerimaan.nik', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('penerimaan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->tgl_penerimaan)) {
            $query->where('penerimaan.tgl_penerimaan', $request->tgl_penerimaan);
        }

        if ($user->hasRole('admin departemen')) {
            $query->where('penerimaan.nik', $nik);
            $query->where('penerimaan.kode_cabang', $kode_cabang);
        }
        $penerimaan = $query->paginate(10);

        $karyawan = DB::table('karyawan')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('penerimaan.index', compact('penerimaan', 'karyawan', 'cabang'));
    }

}
