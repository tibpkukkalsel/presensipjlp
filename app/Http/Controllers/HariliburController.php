<?php

namespace App\Http\Controllers;

use App\Models\Harilibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HariliburController extends Controller
{
    public function index()
    {

        $query = Harilibur::query();
        $query->orderBy('kode_libur', 'desc');
        $harilibur = $query->paginate(10);
        return view('harilibur.index', compact('harilibur'));
    }

    public function create()
    {
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('harilibur.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        //LB24001
        $tahun = date('Y', strtotime($request->tanggal_libur));
        $thn = substr($tahun, 2, 2);
        $lastlibur = DB::table('harilibur')
            ->whereRaw('YEAR(tanggal_libur)="' . $tahun . '"')
            ->orderBy('kode_libur', 'desc')
            ->first();
        $lastkodelibur = $lastlibur != null ? $lastlibur->kode_libur : "";
        $format = "LB"  . $thn;
        $kode_libur = buatkode($lastkodelibur, $format, 3);
        $kode_cabang = !empty($request->kode_cabang) ? $request->kode_cabang : 'ALL';

        DB::beginTransaction();
        try {
            DB::table('harilibur')
                ->insert([
                    'kode_libur' => $kode_libur,
                    'tanggal_libur' => $request->tanggal_libur,
                    'kode_cabang' => $kode_cabang,
                    'keterangan' => $request->keterangan
                ]);

            // Otomatis terapkan hari libur ke SEMUA pegawai
            $pegawai = DB::table('pegawai')->select('nik')->get();
            $detail = [];
            foreach ($pegawai as $p) {
                $detail[] = [
                    'kode_libur' => $kode_libur,
                    'nik' => $p->nik
                ];
            }
            if (!empty($detail)) {
                DB::table('harilibur_detail')->insert($detail);
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Hari Libur berhasil disimpan dan otomatis diterapkan untuk semua pegawai.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }


    public function edit(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $harilibur = DB::table('harilibur')->where('kode_libur', $kode_libur)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('harilibur.edit', compact('cabang', 'harilibur'));
    }


    public function update(Request $request, $kode_libur)
    {
        $kode_cabang = !empty($request->kode_cabang) ? $request->kode_cabang : 'ALL';
        DB::beginTransaction();
        try {
            DB::table('harilibur')
                ->where('kode_libur', $kode_libur)
                ->update([
                    'tanggal_libur' => $request->tanggal_libur,
                    'kode_cabang' => $kode_cabang,
                    'keterangan' => $request->keterangan
                ]);

            // Pastikan semua pegawai tetap terdaftar di harilibur_detail
            $existingNiks = DB::table('harilibur_detail')->where('kode_libur', $kode_libur)->pluck('nik')->toArray();
            $pegawai = DB::table('pegawai')->select('nik')->get();
            $detail = [];
            foreach ($pegawai as $p) {
                if (!in_array($p->nik, $existingNiks)) {
                    $detail[] = [
                        'kode_libur' => $kode_libur,
                        'nik' => $p->nik
                    ];
                }
            }
            if (!empty($detail)) {
                DB::table('harilibur_detail')->insert($detail);
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }

    public function delete($kode_libur)
    {
        DB::beginTransaction();
        try {
            DB::table('harilibur_detail')->where('kode_libur', $kode_libur)->delete();
            DB::table('harilibur')->where('kode_libur', $kode_libur)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }


    public function setkaryawanlibur($kode_libur)
    {
        $harilibur = DB::table('harilibur')
            ->leftJoin('cabang', 'harilibur.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_libur', $kode_libur)->first();
        return view('harilibur.setkaryawanlibur', compact('harilibur'));
    }


    public function setlistkaryawanlibur($kode_libur)
    {
        return view('harilibur.setlistkaryawanlibur', compact('kode_libur'));
    }


    public function getsetlistkaryawanlibur($kode_libur)
    {
        $harilibur = DB::table('harilibur')->where('kode_libur', $kode_libur)->first();
        $query = DB::table('pegawai')
            ->select('pegawai.*', 'hariliburdetail.nik as ceknik')
            ->leftJoin(
                DB::raw("(
                SELECT nik FROM harilibur_detail
                WHERE kode_libur = '$kode_libur'
            ) hariliburdetail"),
                function ($join) {
                    $join->on('pegawai.nik', '=', 'hariliburdetail.nik');
                }
            );

        if ($harilibur && !empty($harilibur->kode_cabang) && $harilibur->kode_cabang != 'ALL') {
            $query->where('kode_cabang', $harilibur->kode_cabang);
        }

        $karyawan = $query->orderBy('nama_lengkap')->get();
        return view('harilibur.getsetlistkaryawanlibur', compact('karyawan', 'kode_libur'));
    }


    public function storekaryawanlibur(Request $request)
    {
        try {
            $cek = DB::table('harilibur_detail')
                ->where('kode_libur', $request->kode_libur)
                ->where('nik', $request->nik)->count();
            if ($cek > 0) {
                return 1;
            }
            DB::table('harilibur_detail')->insert([
                'kode_libur' => $request->kode_libur,
                'nik' => $request->nik,
            ]);
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function removekaryawanlibur(Request $request)
    {
        try {
            DB::table('harilibur_detail')
                ->where('kode_libur', $request->kode_libur)
                ->where('nik', $request->nik)
                ->delete();
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function getkaryawanlibur($kode_libur)
    {
        $karyawanlibur = DB::table('harilibur_detail')
            ->join('pegawai', 'harilibur_detail.nik', '=', 'pegawai.nik')
            ->where('kode_libur', $kode_libur)
            ->get();

        return view('harilibur.getkaryawanlibur', compact('karyawanlibur', 'kode_libur'));
    }
}
