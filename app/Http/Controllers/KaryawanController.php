<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $query = Karyawan::query();
        $query->select('pegawai.*', 'nama_dept');
        $query->join('seksi', 'pegawai.kode_dept', '=', 'seksi.kode_dept');
        $query->orderBy('nama_lengkap');
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('pegawai.kode_dept', $request->kode_dept);
        }

        if (!empty($request->kode_cabang)) {
            $query->where('pegawai.kode_cabang', $request->kode_cabang);
        }

        if ($user->hasRole('admin departemen')) {
            $query->where('pegawai.kode_dept', $kode_dept);
            $query->where('pegawai.kode_cabang', $kode_cabang);
        }
        $karyawan = $query->paginate(10);

        $departemen = DB::table('seksi')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('karyawan.index', compact('karyawan', 'departemen', 'cabang'));
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('12345');
        $kode_cabang = $request->kode_cabang;
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data =  [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
                'kode_cabang' => $kode_cabang
            ];
            $simpan = DB::table('pegawai')->insert($data);
            if ($simpan) {
                $this->autoSetJamKerjaDept($kode_cabang, $kode_dept);
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) {
                $message = "Data dengan Nik " . $nik . " Sudah Ada";
            } else {
                $message = "Hubungi IT";
            }
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan ' . $message]);
        }
    }

    public function edit(Request $request)
    {
        $nik = $request->nik;
        $departemen = DB::table('seksi')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        $karyawan = DB::table('pegawai')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('departemen', 'karyawan', 'cabang'));
    }

    public function update($nik, Request $request)
    {
        $nik = Crypt::decrypt($nik);
        $nik_baru = $request->nik_baru;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $kode_cabang = $request->kode_cabang;
        $password = Hash::make('12345');
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }


        $ceknik = DB::table('pegawai')
            ->where('nik', $nik_baru)
            ->where('nik', '!=', $nik)
            ->count();
        if ($ceknik > 0) {
            return Redirect::back()->with(['warning' => 'Nik Sudah Digunakan']);
        }
        try {
            $data =  [
                'nik' => $nik_baru,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password,
                'kode_cabang' => $kode_cabang
            ];
            $update = DB::table('pegawai')->where('nik', $nik)->update($data);
            if ($update) {
                $this->autoSetJamKerjaDept($kode_cabang, $kode_dept);
                if ($request->hasFile('foto')) {
                    $folderPath = "public/uploads/karyawan/";
                    $folderPathOld = "public/uploads/karyawan/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Update']);
            }
        } catch (\Exception $e) {
            dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete($nik)
    {
        $delete = DB::table('pegawai')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }

    public function resetpassword($nik)
    {
        $nik = Crypt::decrypt($nik);
        $password = Hash::make('12345');
        $reset = DB::table('pegawai')->where('nik', $nik)->update([
            'password' => $password
        ]);

        if ($reset) {
            return Redirect::back()->with(['success' => 'Data Password Berhasil di Reset']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Password Gagal di Reset']);
        }
    }

    public function lockandunlocklocation($nik)
    {
        try {
            $karyawan = DB::table('pegawai')->where('nik', $nik)->first();
            $status_location = $karyawan->status_location;
            if ($status_location == '1') {
                DB::table('pegawai')->where('nik', $nik)->update([
                    'status_location' => '0'
                ]);
            } else {
                DB::table('pegawai')->where('nik', $nik)->update([
                    'status_location' => '1'
                ]);
            }

            return Redirect::back()->with(['success' => 'Status Location Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Status Location Gagal Diupdate']);
        }
    }

    public function lockandunlockjamkerja($nik)
    {
        try {
            $karyawan = DB::table('pegawai')->where('nik', $nik)->first();
            $status_jam_kerja = $karyawan->status_jam_kerja;
            if ($status_jam_kerja == '1') {
                DB::table('pegawai')->where('nik', $nik)->update([
                    'status_jam_kerja' => '0'
                ]);
            } else {
                DB::table('pegawai')->where('nik', $nik)->update([
                    'status_jam_kerja' => '1'
                ]);
            }

            return Redirect::back()->with(['success' => 'Status Jam Kerja Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Status Jam Kerja Gagal Diupdate']);
        }
    }

    public function lockandunlockscan($nik)
    {
        try {
            $karyawan = DB::table('pegawai')->where('nik', $nik)->first();
            $status_scan = $karyawan->status_scan ?? '1';
            $new_status = ($status_scan == '1') ? '0' : '1';
            DB::table('pegawai')->where('nik', $nik)->update([
                'status_scan' => $new_status
            ]);

            $msg = ($new_status == '1') 
                ? 'Fitur Scan Wajah Diaktifkan untuk ' . $karyawan->nama_lengkap 
                : 'Fitur Scan Wajah Dinonaktifkan (Mode Tombol Foto Aktif) untuk ' . $karyawan->nama_lengkap;

            return Redirect::back()->with(['success' => $msg]);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Status Scan Wajah Gagal Diupdate: ' . $e->getMessage()]);
        }
    }

    private function autoSetJamKerjaDept($kode_cabang, $kode_dept)
    {
        if (empty($kode_cabang) || empty($kode_dept)) return;

        $exists = DB::table('konfigurasi_jk_dept')
            ->where('kode_cabang', $kode_cabang)
            ->where('kode_dept', $kode_dept)
            ->first();

        if (!$exists) {
            $kode_jk_dept = 'J' . $kode_cabang . $kode_dept;
            DB::table('konfigurasi_jk_dept')->insert([
                'kode_jk_dept' => $kode_jk_dept,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept
            ]);

            // Ambil template jadwal dari seksi lain di kantor/cabang yang sama
            $template = DB::table('konfigurasi_jk_dept')
                ->where('kode_cabang', $kode_cabang)
                ->where('kode_jk_dept', '!=', $kode_jk_dept)
                ->first();

            if ($template) {
                $details = DB::table('konfigurasi_jk_dept_detail')
                    ->where('kode_jk_dept', $template->kode_jk_dept)
                    ->get();
                foreach ($details as $d) {
                    DB::table('konfigurasi_jk_dept_detail')->insert([
                        'kode_jk_dept' => $kode_jk_dept,
                        'hari' => $d->hari,
                        'kode_jam_kerja' => $d->kode_jam_kerja
                    ]);
                }
            } else {
                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $defaultJk = DB::table('jam_kerja')->first();
                $kodeJk = $defaultJk ? $defaultJk->kode_jam_kerja : 'JK01';
                foreach ($hariList as $h) {
                    DB::table('konfigurasi_jk_dept_detail')->insert([
                        'kode_jk_dept' => $kode_jk_dept,
                        'hari' => $h,
                        'kode_jam_kerja' => ($h == 'Sabtu') ? null : $kodeJk
                    ]);
                }
            }
        }
    }
}
