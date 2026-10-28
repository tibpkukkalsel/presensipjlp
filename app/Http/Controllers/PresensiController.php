<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pengajuanizin;
use App\Models\User;
use App\Models\Dapartemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{

    public function gethari($hari)
    {
        // $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return $hari_ini;
    }


    public function create($kode_jam_kerja)
    {

        $kode_jam_kerja = $kode_jam_kerja != "null" ? Crypt::decrypt($kode_jam_kerja) : $kode_jam_kerja;
        $nik = Auth::guard('karyawan')->user()->nik;
        $hariini = date("Y-m-d");
        $jamsekarang = date("H:i");
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hariini)));
        $cekpresensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $ceklintashari_presensi = $cekpresensi_sebelumnya != null  ? $cekpresensi_sebelumnya->lintashari : 0;

        if ($ceklintashari_presensi == 1) {
            if ($jamsekarang < "08:00") {
                $hariini = $tgl_sebelumnya;
            }
        }
        $namahari = $this->gethari(date('D', strtotime($hariini)));


        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $presensi = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

        // Cek apakah hari ini adalah Hari Libur
        $datalibur = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->where('harilibur.tanggal_libur', $hariini)
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.kode_libur', 'harilibur.tanggal_libur', 'harilibur.keterangan')
            ->first();

        if (!$datalibur && $datapresensi != null && $datapresensi->status == 'l') {
            $izin = DB::table('pengajuan_izin')->where('kode_izin', $datapresensi->kode_izin)->first();
            $datalibur = (object)[
                'kode_libur' => 'LIBUR',
                'tanggal_libur' => $hariini,
                'keterangan' => ($izin && !empty($izin->keterangan)) ? $izin->keterangan : 'Libur Nasional / Bersama'
            ];
        }

        if ($datalibur != null) {
            return view('presensi.notiflibur', compact('datalibur'));
        }

        if ($kode_jam_kerja == "null") {
            //Cek Jam Kerja By Date
            $jamkerja = DB::table('konfigurasi_jamkerja_by_date')
                ->join('jam_kerja', 'konfigurasi_jamkerja_by_date.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('nik', $nik)
                ->where('tanggal', $hariini)
                ->first();

            //Jika Tidak Memiliki Jam Kerja By Date
            if ($jamkerja == null) {
                //Cek Jam Kerja harian / Jam Kerja Khusus / Jam Kerja Per Orangannya
                $jamkerja = DB::table('konfigurasi_jamkerja')
                    ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('nik', $nik)->where('hari', $namahari)->first();

                // Jika Jam Kerja Harian Kosong
                if ($jamkerja == null) {
                    $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                        ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                        ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                        ->where('kode_dept', $kode_dept)
                        ->where('kode_cabang', $kode_cabang)
                        ->where('hari', $namahari)->first();

                    // Fallback 1: Jika seksi belum ada jadwal khusus, cari jadwal dari seksi lain di kantor/cabang yang sama
                    if ($jamkerja == null) {
                        $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                            ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                            ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('kode_cabang', $kode_cabang)
                            ->where('hari', $namahari)->first();
                    }

                    // Fallback 2: Jika masih kosong di hari kerja (Senin - Jumat), gunakan jam kerja kantor default
                    if ($jamkerja == null && !in_array($namahari, ['Sabtu', 'Minggu'])) {
                        $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', 'JK01')->first() ?? DB::table('jam_kerja')->first();
                    }
                }
            }
        } else {
            $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        }






        if ($datapresensi != null && $datapresensi->status != "h") {
            return view('presensi.notifizin');
        } else if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        } else {
            $status_scan_global = DB::table('konfigurasi_scan')->where('id', 1)->value('status_scan') ?? 1;
            $status_scan_user = Auth::guard('karyawan')->user()->status_scan ?? '1';
            $scan_aktif = ($status_scan_global == 1 && $status_scan_user == '1') ? 1 : 0;

            return view('presensi.create', compact('cek', 'datapresensi', 'lok_kantor', 'jamkerja', 'hariini', 'kode_jam_kerja', 'scan_aktif'));
        }
    }

    public function store(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nik = Auth::guard('karyawan')->user()->nik;
        $status_location = Auth::guard('karyawan')->user()->status_location;
        $hariini = date("Y-m-d");
        $jamsekarang = date("H:i");
        $tgl_sebelumnya = date('Y-m-d', strtotime("-1 days", strtotime($hariini)));
        $cekpresensi_sebelumnya = DB::table('presensi')
            ->join('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $tgl_sebelumnya)
            ->where('nik', $nik)
            ->first();

        $ceklintashari_presensi = $cekpresensi_sebelumnya != null  ? $cekpresensi_sebelumnya->lintashari : 0;

        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
        $tgl_presensi = $ceklintashari_presensi == 1 && $jamsekarang < "08:00" ? $tgl_sebelumnya : date("Y-m-d");

        // Cek apakah hari ini adalah Hari Libur
        $datalibur = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->where('harilibur.tanggal_libur', $tgl_presensi)
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.keterangan')
            ->first();

        $presensiCek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->first();
        if (!$datalibur && $presensiCek != null && $presensiCek->status == 'l') {
            $izin = DB::table('pengajuan_izin')->where('kode_izin', $presensiCek->kode_izin)->first();
            $datalibur = (object)[
                'keterangan' => ($izin && !empty($izin->keterangan)) ? $izin->keterangan : 'Libur Nasional / Bersama'
            ];
        }

        if ($datalibur != null) {
            $ketLibur = !empty($datalibur->keterangan) ? $datalibur->keterangan : 'Hari Libur';
            echo "error|Hari ini adalah Hari Libur (" . $ketLibur . "), Anda tidak dapat melakukan presensi!|in";
            return;
        }

        $jam = date("H:i:s");
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        if (!$lok_kantor) {
            echo "error|Data kantor / cabang tidak ditemukan|radius";
            return;
        }
        $lok = explode(",", $lok_kantor->lokasi_cabang);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];

        $lokasi = $request->lokasi;
        if (empty($lokasi)) {
            echo "error|Titik lokasi GPS tidak terdeteksi. Izinkan akses GPS pada browser Anda.|radius";
            return;
        }
        $lokasiuser = explode(",", $lokasi);
        if (count($lokasiuser) < 2) {
            echo "error|Format koordinat lokasi tidak valid.|radius";
            return;
        }
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        //Cek Jam Kerja Karyawan
        $namahari = $this->gethari(date('D', strtotime($tgl_presensi)));

        if ($kode_jam_kerja == "null") {
            //Cek Jam Kerja By Date
            $jamkerja = DB::table('konfigurasi_jamkerja_by_date')
                ->join('jam_kerja', 'konfigurasi_jamkerja_by_date.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('nik', $nik)
                ->where('tanggal', $tgl_presensi)
                ->first();

            //Jika Tidak Memiliki Jam Kerja By Date
            if ($jamkerja == null) {
                //Cek Jam Kerja harian / Jam Kerja Khusus / Jam Kerja Per Orangannya
                $jamkerja = DB::table('konfigurasi_jamkerja')
                    ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('nik', $nik)->where('hari', $namahari)->first();

                // Jika Jam Kerja Harian Kosong
                if ($jamkerja == null) {
                    $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                        ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                        ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                        ->where('kode_dept', $kode_dept)
                        ->where('kode_cabang', $kode_cabang)
                        ->where('hari', $namahari)->first();

                    // Fallback 1: Jika seksi belum ada jadwal khusus, cari jadwal dari seksi lain di kantor/cabang yang sama
                    if ($jamkerja == null) {
                        $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                            ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                            ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                            ->where('kode_cabang', $kode_cabang)
                            ->where('hari', $namahari)->first();
                    }

                    // Fallback 2: Jika masih kosong di hari kerja (Senin - Jumat), gunakan jam kerja kantor default
                    if ($jamkerja == null && !in_array($namahari, ['Sabtu', 'Minggu'])) {
                        $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', 'JK01')->first() ?? DB::table('jam_kerja')->first();
                    }
                }
            }
        } else {
            $jamkerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        }

        if ($jamkerja == null) {
            echo "error|Jadwal jam kerja tidak ditemukan untuk hari ini|in";
            return;
        }

        $presensi = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik);
        $cek = $presensi->count();
        $datapresensi = $presensi->first();

        // Validasi Status Absen sesuai pilihan pegawai ('in' atau 'out')
        $status_absen_req = ($request->status_absen == "out") ? "out" : "in";

        if ($status_absen_req == "in") {
            // Pegawai bermaksud Absen Masuk: Tolak jika sudah pernah absen masuk hari ini
            if ($datapresensi != null && !empty($datapresensi->jam_in)) {
                echo "error|Anda sudah berhasil melakukan presensi masuk hari ini|in";
                return;
            }
            $ket = "in";
        } else {
            // Pegawai bermaksud Absen Pulang: Tolak jika sudah pernah absen pulang hari ini
            if ($datapresensi != null && !empty($datapresensi->jam_out)) {
                echo "error|Anda sudah berhasil melakukan presensi pulang hari ini|out";
                return;
            }
            $ket = "out";
        }

        $image = $request->image;
        if (empty($image) || strpos($image, ';base64') === false) {
            echo "error|Foto selfie tidak terdeteksi. Pastikan webcam / kamera diizinkan.|" . $ket;
            return;
        }

        $folderPath = "public/uploads/absensi/";
        $timeSuffix = date('His');
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket . "-" . $timeSuffix;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        if (empty($image_base64) || !@imagecreatefromstring($image_base64)) {
            echo "error|Format foto selfie tidak valid atau rusak.|" . $ket;
            return;
        }
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $tgl_pulang = $jamkerja->lintashari == 1 ? date('Y-m-d', strtotime("+ 1 days", strtotime($tgl_presensi))) : $tgl_presensi;
        $jam_pulang = $hariini . " " . $jam;
        $jamkerja_pulang = $tgl_pulang . " " . $jamkerja->jam_pulang;
        $datakaryawan = DB::table('pegawai')->where('nik', $nik)->first();
        $no_hp = $datakaryawan ? $datakaryawan->no_hp : '';
        $datalokasi = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $nama_lokasi = $datalokasi ? $datalokasi->nama_cabang : '';
        if ($status_location == 1 && $radius > $lok_kantor->radius_cabang) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor|radius";
        } else {
            if ($status_absen_req == "out") {
                // PROSES PRESENSI PULANG
                $is_pulang_cepat = ($jam_pulang < $jamkerja_pulang);
                $status_out = 'tepat_waktu';
                $selisih_out = 0;
                $pesan_out = "Terimakasih, Hati Hati Di Jalan";

                if ($is_pulang_cepat) {
                    if (isset($datapresensi->status_out) && $datapresensi->status_out == 'izin_pulang_cepat') {
                        $status_out = 'izin_pulang_cepat';
                        $pesan_out = "Terimakasih, Anda Pulang Sesuai Izin Pulang Cepat";
                    } else {
                        $status_out = 'pulang_duluan';
                        $pesan_out = "Terimakasih, Anda Pulang Lebih Awal (Pulang Duluan)";
                        $pJadwal = explode(':', $jamkerja->jam_pulang);
                        $pJam = explode(':', $jam);
                        $tJadwal = mktime((int)$pJadwal[0], (int)$pJadwal[1], (int)($pJadwal[2] ?? 0), 1, 1, 1);
                        $tJam = mktime((int)$pJam[0], (int)$pJam[1], (int)($pJam[2] ?? 0), 1, 1, 1);
                        $diffSec = $tJadwal - $tJam;
                        $selisih_out = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
                    }
                }

                if ($datapresensi != null) {
                    // Pegawai sudah pernah absen masuk sebelumnya -> Update jam_out
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi,
                        'status_out' => $status_out,
                        'selisih_out' => $selisih_out
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        $this->simpanFotoAbsensi($fileName, $image_base64);
                        echo "success|" . $pesan_out . "|out";

                        if (!empty($no_hp)) {
                            try {
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://wagateway.pedasalami.com/send-message',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_CONNECTTIMEOUT => 2,
                                    CURLOPT_TIMEOUT => 3,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => array('message' => 'Terimakasih Sudah Melakukan Absen Pulang, Anda Melakukan Absen Pada Jam ' . $jam, 'number' => $no_hp, 'file_dikirim' => ''),
                                ));
                                $response = curl_exec($curl);
                                curl_close($curl);
                            } catch (\Throwable $e) {}
                        }
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                    }
                } else {
                    // Pegawai tidak absen masuk, lalu langsung absen pulang -> Insert record baru
                    $data_pulang = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => null,
                        'foto_in' => null,
                        'lokasi_in' => null,
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h',
                        'status_in' => null,
                        'status_out' => $status_out,
                        'selisih_in' => 0,
                        'selisih_out' => $selisih_out
                    ];
                    $simpan = DB::table('presensi')->insert($data_pulang);
                    if ($simpan) {
                        $this->simpanFotoAbsensi($fileName, $image_base64);
                        echo "success|" . $pesan_out . "|out";

                        if (!empty($no_hp)) {
                            try {
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://wagateway.pedasalami.com/send-message',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_CONNECTTIMEOUT => 2,
                                    CURLOPT_TIMEOUT => 3,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => array('message' => 'Terimakasih Sudah Melakukan Absen Pulang, Anda Melakukan Absen Pada Jam ' . $jam, 'number' => $no_hp, 'file_dikirim' => ''),
                                ));
                                $response = curl_exec($curl);
                                curl_close($curl);
                            } catch (\Throwable $e) {}
                        }
                    } else {
                        echo "error|Maaf Gagal absen pulang, Hubungi Tim It|out";
                    }
                }
            } else {
                // PROSES PRESENSI MASUK
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error|Maaf Belum Waktunya Melakukan Presensi Masuk|in";
                } else {
                    $status_in = 'tepat_waktu';
                    $selisih_in = 0;
                    if ($jam > $jamkerja->jam_masuk) {
                        $status_in = 'terlambat';
                        $pJadwal = explode(':', $jamkerja->jam_masuk);
                        $pJam = explode(':', $jam);
                        $tJadwal = mktime((int)$pJadwal[0], (int)$pJadwal[1], (int)($pJadwal[2] ?? 0), 1, 1, 1);
                        $tJam = mktime((int)$pJam[0], (int)$pJam[1], (int)($pJam[2] ?? 0), 1, 1, 1);
                        $diffSec = $tJam - $tJadwal;
                        $selisih_in = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
                    }

                    $data = [
                        'nik' => $nik,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_in' => $jam,
                        'foto_in' => $fileName,
                        'lokasi_in' => $lokasi,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => 'h',
                        'status_in' => $status_in,
                        'status_out' => 'tepat_waktu',
                        'selisih_in' => $selisih_in,
                        'selisih_out' => 0
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        $this->simpanFotoAbsensi($fileName, $image_base64);
                        $pesan_in = ($status_in == 'terlambat')
                            ? "Terimakasih, Anda Tercatat Terlambat " . $selisih_in . " Menit"
                            : "Terimakasih, Selamat Bekerja";
                        echo "success|" . $pesan_in . "|in";

                        if (!empty($no_hp)) {
                            try {
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => 'https://wagateway.pedasalami.com/send-message',
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_CONNECTTIMEOUT => 2,
                                    CURLOPT_TIMEOUT => 3,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => array('message' => 'Terimakasih Sudah Melakukan Absen Masuk, Anda Melakukan Absen Pada Jam ' . $jam, 'number' => $no_hp, 'file_dikirim' => ''),
                                ));
                                $response = curl_exec($curl);
                                curl_close($curl);
                            } catch (\Throwable $e) {}
                        }
                    } else {
                        echo "error|Maaf Gagal absen, Hubungi Tim It|in";
                    }
                }
            }
        }
    }

    /**
     * Simpan file foto absensi ke berbagai target path penyimpanan
     * agar kompatibel dengan shared hosting (cPanel), VPS, maupun local
     */
    private function simpanFotoAbsensi($fileName, $image_base64)
    {
        if (empty($fileName) || empty($image_base64)) {
            return false;
        }

        // Auto Center-Crop ke 1:1 Bujur Sangkar (Square) dan normalisasi format PNG
        // Memastikan wajah pengguna tepat berada di tengah foto secara simetris sempurna tanpa distorsi
        try {
            $srcImg = @imagecreatefromstring($image_base64);
            if ($srcImg) {
                $origW = imagesx($srcImg);
                $origH = imagesy($srcImg);
                if ($origW > 0 && $origH > 0) {
                    $cropSize = min($origW, $origH);
                    $cropX = (int)(($origW - $cropSize) / 2);
                    $cropY = (int)(($origH - $cropSize) / 2);

                    $targetSize = min($cropSize, 480);
                    $croppedImg = imagecreatetruecolor($targetSize, $targetSize);
                    imagecopyresampled($croppedImg, $srcImg, 0, 0, $cropX, $cropY, $targetSize, $targetSize, $cropSize, $cropSize);

                    ob_start();
                    imagepng($croppedImg);
                    $image_base64 = ob_get_clean();
                    if (function_exists('imagedestroy')) {
                        @imagedestroy($croppedImg);
                    }
                }
                if (function_exists('imagedestroy')) {
                    @imagedestroy($srcImg);
                }
            }
        } catch (\Throwable $e) {
            \Log::warning("Gagal auto-crop foto absensi: " . $e->getMessage());
        }

        // 1. Target standar Laravel Storage: storage/app/public/uploads/absensi/
        try {
            $storageDir = storage_path('app/public/uploads/absensi');
            if (!file_exists($storageDir)) {
                @mkdir($storageDir, 0777, true);
            }
            Storage::put('public/uploads/absensi/' . $fileName, $image_base64);
            @file_put_contents($storageDir . '/' . $fileName, $image_base64);
        } catch (\Throwable $e) {
            \Log::warning("Gagal simpan ke storage/app/public/uploads/absensi: " . $e->getMessage());
        }

        // 2. Target public storage: public/storage/uploads/absensi/
        // Berguna jika hosting tidak mendukung symlink atau storage:link belum dibuat
        try {
            $publicStorageDir = public_path('storage/uploads/absensi');
            if (!file_exists($publicStorageDir)) {
                @mkdir($publicStorageDir, 0777, true);
            }
            @file_put_contents($publicStorageDir . '/' . $fileName, $image_base64);
        } catch (\Throwable $e) {}

        // 3. Target public uploads: public/uploads/absensi/
        try {
            $publicUploadsDir = public_path('uploads/absensi');
            if (!file_exists($publicUploadsDir)) {
                @mkdir($publicUploadsDir, 0777, true);
            }
            @file_put_contents($publicUploadsDir . '/' . $fileName, $image_base64);
        } catch (\Throwable $e) {}

        return true;
    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function editlokasi()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        $cabang = DB::table('cabang')->get();

        return view('presensi.editlokasi', compact('karyawan', 'cabang'));
    }

    public function cabang()
    {
        $cabang = DB::table('cabang')->get();

    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $request->validate([
            'foto' => 'image|mimes:png,jpg|max:1024'
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }
        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data gagal Di Update']);
        }
    }

    public function updatelokasi(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        $request->validate([
            'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
            'foto' => 'nullable|image|mimes:png,jpg|max:1024'
        ]);

        $kode_cabang = $request->kode_cabang ?? $karyawan->kode_cabang;

        // Jika status_location karyawan dikunci (1), karyawan tidak boleh memindahkan kantor cabangnya sendiri
        if ($karyawan->status_location == 1 && !empty($request->kode_cabang) && $request->kode_cabang != $karyawan->kode_cabang) {
            return Redirect::back()->with(['error' => 'Lokasi cabang Anda dikunci oleh Administrator dan tidak dapat diubah secara mandiri']);
        }

        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        $data = [
            'kode_cabang' => $kode_cabang,
            'foto' => $foto
        ];

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect('dashboard')->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data gagal Di Update']);
        }
    }


    public function histori(Request $request)
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulan = $request->bulan ?? (int)date('m');
        $tahun = $request->tahun ?? (int)date('Y');
        $user = Auth::guard('karyawan')->user();
        $nik = $user ? $user->nik : null;
        $kode_cabang = $user ? $user->kode_cabang : null;

        $histori = $this->queryHistoriData($nik, $kode_cabang, $bulan, $tahun);
        $historiJson = json_encode($histori);
        $defaultJamKerja = DB::table('jam_kerja')->first();

        return view('presensi.histori', compact('namabulan', 'bulan', 'tahun', 'histori', 'historiJson', 'defaultJamKerja'));
    }

    private function queryHistoriData($nik, $kode_cabang, $bulan, $tahun)
    {
        if (empty($nik)) {
            return collect([]);
        }

        $histori = DB::table('presensi')
            ->select('presensi.*', 'keterangan', 'jam_kerja.*', 'doc_sid', 'nama_cuti')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . (int)$bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . (int)$tahun . '"')
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        // Ambil data hari libur pada bulan dan tahun ini untuk pegawai
        $harilibur = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->whereRaw('MONTH(harilibur.tanggal_libur)="' . (int)$bulan . '"')
            ->whereRaw('YEAR(harilibur.tanggal_libur)="' . (int)$tahun . '"')
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.tanggal_libur', 'harilibur.keterangan')
            ->distinct()
            ->get();

        $existingDates = $histori->pluck('tgl_presensi')->toArray();
        $historyList = $histori->map(function ($item) use ($harilibur) {
            if ($item->status == 'l' && empty($item->keterangan)) {
                $hl = $harilibur->firstWhere('tanggal_libur', $item->tgl_presensi);
                if ($hl) {
                    $item->keterangan = $hl->keterangan;
                }
            }
            $item->foto_in_url = !empty($item->foto_in) ? asset('storage/uploads/absensi/' . $item->foto_in) . '?v=' . time() : null;
            $item->foto_out_url = !empty($item->foto_out) ? asset('storage/uploads/absensi/' . $item->foto_out) . '?v=' . time() : null;
            return $item;
        });

        foreach ($harilibur as $hl) {
            if (!in_array($hl->tanggal_libur, $existingDates)) {
                $historyList->push((object)[
                    'id' => null,
                    'nik' => $nik,
                    'tgl_presensi' => $hl->tanggal_libur,
                    'jam_in' => null,
                    'jam_out' => null,
                    'foto_in' => null,
                    'foto_out' => null,
                    'foto_in_url' => null,
                    'foto_out_url' => null,
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

        return $historyList->sortByDesc('tgl_presensi')->values();
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan ?? (int)date('m');
        $tahun = $request->tahun ?? (int)date('Y');
        $user = Auth::guard('karyawan')->user();
        $nik = $user ? $user->nik : null;
        $kode_cabang = $user ? $user->kode_cabang : null;

        $histori = $this->queryHistoriData($nik, $kode_cabang, $bulan, $tahun);
        $defaultJamKerja = DB::table('jam_kerja')->first();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'bulan' => (int)$bulan,
                'tahun' => (int)$tahun,
                'histori' => $histori,
                'default_jam_kerja' => $defaultJamKerja
            ]);
        }

        return view('presensi.gethistori', compact('histori'));
    }

    public function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $dataizin = DB::table('pengajuan_izin')
                ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('nik', $nik)
                ->whereRaw('MONTH(tgl_izin_dari)="' . $request->bulan . '"')
                ->whereRaw('YEAR(tgl_izin_dari)="' . $request->tahun . '"')
                ->get();
        } else {
            $dataizin = DB::table('pengajuan_izin')
                ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
                ->orderBy('tgl_izin_dari', 'desc')
                ->where('nik', $nik)->limit(5)->orderBy('tgl_izin_dari', 'desc')
                ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.izin', compact('dataizin', 'namabulan'));
    }


    public function buatizin()
    {

        return view('presensi.buatizin');
    }


    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function penerimaan(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;

        if (!empty($request->bulan) && !empty($request->tahun)) {
            $datapenerimaan = DB::table('penerimaan')
                ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
                ->orderBy('tgl_penerimaan', 'desc')
                ->where('nik', $nik)
                ->whereRaw('MONTH(tgl_penerimaan)="' . $request->bulan . '"')
                ->whereRaw('YEAR(tgl_penerimaan)="' . $request->tahun . '"')
                ->get();
        } else {
            $datapenerimaan = DB::table('penerimaan')
                ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
                ->orderBy('tgl_penerimaan', 'desc')
                ->where('nik', $nik)->limit(5)->orderBy('tgl_penerimaan', 'desc')
                ->get();
        }

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.penerimaan', compact('datapenerimaan', 'namabulan'));
    }

    public function buatpenerimaan()
    {

        return view('presensi.buatpenerimaan');
    }



    public function monitoring()
    {
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        $departemen = DB::table('seksi')->orderBy('kode_dept')->get();
        return view('presensi.monitoring', compact('cabang', 'departemen'));
    }

    public function getpresensi(Request $request)
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $tanggal = $request->tanggal;
        $datalibur = getkaryawanlibur($tanggal, $tanggal);

        $query = DB::table('pegawai as karyawan');
        $query->selectRaw(
            'karyawan.nik, nama_lengkap, karyawan.foto as foto_pegawai, karyawan.jabatan, karyawan.kode_dept, seksi.nama_dept, karyawan.kode_cabang, cabang.nama_cabang as lokasi,
        datapresensi.id,jam_in,jam_out,foto_in,foto_out,lokasi_in,lokasi_out,
        datapresensi.status,status_in,status_out,selisih_in,selisih_out,jam_masuk, nama_jam_kerja, jam_pulang, keterangan'
        );
        $query->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('seksi', 'karyawan.kode_dept', '=', 'seksi.kode_dept');
        $query->leftJoin(
            DB::raw("(
                SELECT
                presensi.nik,presensi.id,jam_in,jam_out,foto_in,foto_out,lokasi_in,lokasi_out,presensi.status,presensi.status_in,presensi.status_out,presensi.selisih_in,presensi.selisih_out,jam_masuk, nama_jam_kerja, jam_pulang, keterangan
                FROM presensi
                LEFT JOIN  jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                WHERE tgl_presensi = '$tanggal'
            ) datapresensi"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'datapresensi.nik');
            }
        );

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        if ($user->hasRole('admin departemen')) {
            $query->where('karyawan.kode_cabang', $kode_cabang);
            $query->where('karyawan.kode_dept', $kode_dept);
        }
        $query->orderBy('nama_lengkap');
        $presensi = $query->get();

        return view('presensi.getpresensi', compact('presensi', 'tanggal', 'datalibur'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('presensi.id', $id)
            ->join('pegawai as karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }


    public function laporan()
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        if ($user->hasRole('admin departemen')) {
            $karyawan = DB::table('karyawan')
                ->where('kode_dept', $kode_dept)
                ->where('kode_cabang', $kode_cabang)
                ->orderBy('nama_lengkap')->get();
        } else if ($user->hasRole('administrator')) {
            $karyawan = DB::table('karyawan')
                ->orderBy('nama_lengkap')->get();
        }

        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function laporanpenerimaan()
    {
        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        if ($user->hasRole('admin departemen')) {
            $karyawan = DB::table('karyawan')
                ->where('kode_dept', $kode_dept)
                ->where('kode_cabang', $kode_cabang)
                ->orderBy('nama_lengkap')->get();
        } else if ($user->hasRole('administrator')) {
            $karyawan = DB::table('karyawan')
                ->orderBy('nama_lengkap')->get();
        }

        return view('presensi.laporanpenerimaan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporanpenerimaan(Request $request)
    {

        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $penerimaan = DB::table('penerimaan')
            ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
            ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
            ->where('penerimaan.nik', $nik)
            ->where('penerimaan.status_approved', '1')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('tgl_penerimaan')
            ->get();

            $totalpenerimaan = DB::table('penerimaan')
            ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
            ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
            ->where('penerimaan.nik', $nik)
            ->where('penerimaan.status_approved', '1')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('tgl_penerimaan')
            ->sum(DB::raw('jumlah'));

            $totalr2 = DB::table('penerimaan')
            ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
            ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
            ->where('penerimaan.nik', $nik)
            ->where('penerimaan.status_approved', '1')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('tgl_penerimaan')
            ->sum(DB::raw('r2'));

            $totalr4 = DB::table('penerimaan')
            ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
            ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
            ->where('penerimaan.nik', $nik)
            ->where('penerimaan.status_approved', '1')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('tgl_penerimaan')
            ->sum(DB::raw('r4'));

            $totalnotice = DB::table('penerimaan')
            ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
            ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
            ->where('penerimaan.nik', $nik)
            ->where('penerimaan.status_approved', '1')
            ->whereRaw('MONTH(tgl_penerimaan)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
            ->orderBy('tgl_penerimaan')
            ->sum(DB::raw('notice_keluar'));


        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Penerimaan $time.xls");
            return view('presensi.cetaklaporanpenerimaan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'penerimaan', 'totalpenerimaan', 'totalr2', 'totalr4', 'totalnotice'));
        }
        return view('presensi.cetaklaporanpenerimaan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'penerimaan', 'totalpenerimaan', 'totalr2', 'totalr4', 'totalnotice'));
    }

    public function cetakrekappenerimaan(Request $request)
    {
        $tahun = $request->tahun;
        $kode_dept = $request->kode_dept;
        $kode_cabang = $request->kode_cabang;

        // if ($bulan == 1) {
        //     $bulanlalu = 12;
        //     $tahunlalu = $tahun - 1;
        // } else {
        //     $bulanlalu = $bulan - 1;
        //     $tahunlalu = $tahun;
        // }

        // $dari = $tahunlalu . "-" . $bulanlalu . "-21";
        // $sampai = $tahun . "-" . $bulan . "-20";

        //Februari 2024
        //21 Januari 2024 s/d 20 Februari 2024
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $penerimaan = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap',
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 1, jumlah, 0)) as j1'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 2, jumlah, 0)) as j2'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 3, jumlah, 0)) as j3'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 4, jumlah, 0)) as j4'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 5, jumlah, 0)) as j5'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 6, jumlah, 0)) as j6'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 7, jumlah, 0)) as j7'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 8, jumlah, 0)) as j8'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 9, jumlah, 0)) as j9'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 10, jumlah, 0)) as j10'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 11, jumlah, 0)) as j11'),
          DB::raw('SUM(IF(MONTH(tgl_penerimaan) = 12, jumlah, 0)) as j12'),
          DB::raw('SUM(jumlah) as total')
          )
        ->leftJoin('cabang', 'penerimaan.kode_cabang', '=', 'cabang.kode_cabang')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->groupBy('nik')
        ->get();

        $total1 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '1' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total2 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '2' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total3 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '3' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total4 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '4' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total5 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '5' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total6 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '6' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total7 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '7' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total8 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '8' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total9 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '9' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total10 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '10' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total11 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '11' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $total12 = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('MONTH(tgl_penerimaan)="' . '12' . '"')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));

        $totaltahun = DB::table('penerimaan')
        ->select('penerimaan.*', 'nama_cabang', 'nama_lengkap')
        ->leftJoin('karyawan', 'penerimaan.nik', '=', 'karyawan.nik')
        ->where('penerimaan.status_approved', '1')
        ->whereRaw('YEAR(tgl_penerimaan)="' . $tahun . '"')
        ->orderBy('tgl_penerimaan')
        ->sum(DB::raw('jumlah'));




        $data = ['tahun', 'namabulan', 'penerimaan', 'total1', 'total2', 'total3', 'total4', 'total5', 'total6', 'total7',
        'total8', 'total9', 'total10', 'total11', 'total12', 'totaltahun'];

        //dd($rekap);
        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            // Fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Penerimaan Karyawan $time.xls");
            return view('presensi.cetakrekappenerimaan', compact($data));
        }
            return view('presensi.cetakrekappenerimaan', compact($data));
    }





    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->select('presensi.*', 'keterangan', 'jam_kerja.*')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('presensi.nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        $konfigurasi_laporan = DB::table('konfigurasi_laporan')->where('id', 1)->first();

        if ($request->has('exportexcel') || isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            return response(view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi', 'konfigurasi_laporan')), 200, [
                'Content-Type' => 'application/vnd-ms-excel',
                'Content-Disposition' => 'attachment; filename="Laporan Presensi Karyawan ' . $time . '.xls"',
            ]);
        }

        if ($request->has('html')) {
            return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi', 'konfigurasi_laporan'));
        }

        $tempDir = storage_path('app/mpdf');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Legal',
            'orientation' => 'P',
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_top' => 12,
            'margin_bottom' => 12,
            'tempDir' => $tempDir,
        ]);

        $html = view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi', 'konfigurasi_laporan'))->render();
        $mpdf->WriteHTML($html);
        $namaKaryawan = $karyawan ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $karyawan->nama_lengkap) : $nik;
        $namaFile = 'Laporan_Presensi_' . $namaKaryawan . '_' . ($namabulan[(int)$bulan] ?? $bulan) . '_' . $tahun . '.pdf';

        return response($mpdf->Output($namaFile, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }

    public function rekappenerimaan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('presensi.rekappenerimaan', compact('namabulan', 'departemen', 'cabang'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $departemen = DB::table('departemen')->get();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('presensi.rekap', compact('namabulan', 'departemen', 'cabang'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_dept = $request->kode_dept;
        $kode_cabang = $request->kode_cabang;
        $dari  = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $datalibur = getkaryawanlibur($dari, $sampai);
        $harilibur = DB::table('harilibur')->whereBetween('tanggal_libur', [$dari, $sampai])->get();

        $select_date = "";
        $field_date = "";
        $i = 1;
        $rangetanggal = [];
        while (strtotime($dari) <= strtotime($sampai)) {
            $rangetanggal[] = $dari;

            $select_date .= "MAX(IF(tgl_presensi = '$dari',
            CONCAT(
            IFNULL(jam_in,'NA'),'|',
            IFNULL(jam_out,'NA'),'|',
            IFNULL(presensi.status,'NA'),'|',
            IFNULL(nama_jam_kerja,'NA'),'|',
            IFNULL(jam_masuk,'NA'),'|',
            IFNULL(jam_pulang,'NA'),'|',
            IFNULL(presensi.kode_izin,'NA'),'|',
            IFNULL(keterangan,'NA'),'|',
            IFNULL(total_jam,'NA'),'|',
            IFNULL(lintashari,'NA'),'|',
            IFNULL(awal_jam_istirahat,'NA'),'|',
            IFNULL(akhir_jam_istirahat,'NA'),'|'
            ),NULL)) as tgl_" . $i . ",";

            $field_date .= "tgl_" . $i . ",";
            $i++;
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
        }

        $jmlhari = count($rangetanggal);
        $lastrange = $jmlhari - 1;
        $sampai = $rangetanggal[$lastrange];
        if ($jmlhari == 30) {
            array_push($rangetanggal, NULL);
        } else if ($jmlhari == 29) {
            array_push($rangetanggal, NULL, NULL);
        } else if ($jmlhari == 28) {
            array_push($rangetanggal, NULL, NULL, NULL);
        }

        $table = (new Karyawan())->getTable();
        $query = Karyawan::query()->from($table . ' as karyawan');
        $query->selectRaw(
            "$field_date karyawan.nik, nama_lengkap, jabatan, karyawan.kode_dept, karyawan.kode_cabang"
        );

        $query->leftJoin(
            DB::raw("(
                SELECT
                $select_date
                presensi.nik
                FROM presensi
                LEFT JOIN  jam_kerja ON presensi.kode_jam_kerja = jam_kerja.kode_jam_kerja
                LEFT JOIN pengajuan_izin ON presensi.kode_izin = pengajuan_izin.kode_izin
                WHERE tgl_presensi BETWEEN '$rangetanggal[0]' AND '$sampai'
                GROUP BY nik
            ) presensi"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'presensi.nik');
            }
        );

        if (!empty($kode_dept)) {
            $query->where('karyawan.kode_dept', $kode_dept);
        }
        if (!empty($kode_cabang)) {
            $query->where('karyawan.kode_cabang', $kode_cabang);
        }

        $query->orderBy('nama_lengkap');
        $rekap = $query->get();

        $konfigurasi_laporan = DB::table('konfigurasi_laporan')->where('id', 1)->first();

        // Ambil konfigurasi jadwal kerja untuk validasi hari kerja vs hari libur
        $jadwal_by_date = DB::table('konfigurasi_jamkerja_by_date')
            ->whereBetween('tanggal', [$rangetanggal[0], $sampai])
            ->get()
            ->groupBy('nik');

        $jadwal_pegawai = DB::table('konfigurasi_jamkerja')
            ->get()
            ->groupBy('nik');

        $jadwal_dept = DB::table('konfigurasi_jk_dept_detail')
            ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
            ->get()
            ->groupBy(function ($item) {
                return $item->kode_cabang . '_' . $item->kode_dept;
            });

        $data = [
            'bulan', 'tahun', 'namabulan', 'rekap', 'rangetanggal', 'jmlhari', 
            'datalibur', 'harilibur', 'konfigurasi_laporan',
            'jadwal_by_date', 'jadwal_pegawai', 'jadwal_dept'
        ];
        $viewName = ($request->jenis_laporan == 2) ? 'presensi.cetakrekap_detail' : 'presensi.cetakrekap';

        if ($request->has('exportexcel') || isset($_POST['exportexcel'])) {
            $viewExcel = ($request->jenis_laporan == 2) ? 'presensi.cetakrekap_detailexcel' : 'presensi.cetakrekapexcel';
            $time = date("d-M-Y H:i:s");
            return response(view($viewExcel, compact($data)), 200, [
                'Content-Type' => 'application/vnd-ms-excel',
                'Content-Disposition' => 'attachment; filename="Rekap Presensi Karyawan ' . $time . '.xls"',
            ]);
        }

        if ($request->has('html')) {
            return view($viewName, compact($data));
        }

        $tempDir = storage_path('app/mpdf');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Legal',
            'orientation' => 'L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'tempDir' => $tempDir,
        ]);

        $html = view($viewName, compact($data))->render();
        $mpdf->WriteHTML($html);
        $namaFile = 'Rekap_Presensi_' . ($namabulan[(int)$bulan] ?? $bulan) . '_' . $tahun . '.pdf';

        return response($mpdf->Output($namaFile, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }

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
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_penerimaan', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('penerimaan.nik', 'like', '%' . $request->nik . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('penerimaan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->tgl_penerimaan)) {
            $query->where('penerimaan.tgl_penerimaan', $request->tgl_penerimaan);
        }

        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
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

    public function approvepenerimaan(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_penerimaan = $request->kode_izin_form;
        try {

            DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->update([
                'status_approved' => $status_approved
            ]);
            DB::commit();
            return Redirect('/presensi/realisasi')->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect('/presensi/realisasi')->with(['warning' => 'Data Gagal Diproses']);
        }
    }

    public function batalkanpenerimaan($kode_penerimaan)
    {
        DB::beginTransaction();
        try {
            DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->update([
                'status_approved' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Batalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal DI Batalkan']);
        }

        $update = DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }



    public function izinsakit(Request $request)
    {

        $kode_dept = Auth::guard('user')->user()->kode_dept;
        $kode_cabang = Auth::guard('user')->user()->kode_cabang;
        $user = User::find(Auth::guard('user')->user()->id);

        $query = Pengajuanizin::query();
        $query->select(
            'kode_izin',
            'tgl_izin_dari',
            'tgl_izin_sampai',
            'pengajuan_izin.nik',
            'nama_lengkap',
            'jabatan',
            'status',
            'status_approved',
            'keterangan',
            'karyawan.kode_cabang',
            'karyawan.kode_dept',
            'doc_sid',
            'departemen.nama_dept'
        );
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik')
                   ->join ('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
        }

        if ($user->hasRole('admin departemen')) {
            $query->where('karyawan.kode_dept', $kode_dept);
            $query->where('karyawan.kode_cabang', $kode_cabang);
        }

        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }

        $query->orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());

        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        $departemen = DB::table('departemen')->orderBy('kode_dept')->get();
        return view('presensi.izinsakit', compact('izinsakit', 'cabang', 'departemen'));
    }

    public function getJamKerjaPegawai($nik, $tanggal)
    {
        $namahari = $this->gethari(date('D', strtotime($tanggal)));
        $datakaryawan = DB::table('pegawai')->where('nik', $nik)->first();
        $kode_cabang = $datakaryawan ? $datakaryawan->kode_cabang : null;
        $kode_dept = $datakaryawan ? $datakaryawan->kode_dept : null;

        // 1. Cek Jam Kerja By Date
        $jamkerja = DB::table('konfigurasi_jamkerja_by_date')
            ->join('jam_kerja', 'konfigurasi_jamkerja_by_date.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nik', $nik)
            ->where('tanggal', $tanggal)
            ->first();

        if ($jamkerja == null) {
            // 2. Cek Jam Kerja Harian Pegawai
            $jamkerja = DB::table('konfigurasi_jamkerja')
                ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('nik', $nik)
                ->where('hari', $namahari)
                ->first();

            if ($jamkerja == null && $kode_dept && $kode_cabang) {
                // 3. Cek Jam Kerja Departemen
                $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                    ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                    ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('kode_dept', $kode_dept)
                    ->where('kode_cabang', $kode_cabang)
                    ->where('hari', $namahari)
                    ->first();
            }

            // Fallback: Jika seksi belum ada jadwal khusus, cari jadwal dari seksi lain di kantor/cabang yang sama
            if ($jamkerja == null && $kode_cabang) {
                $jamkerja = DB::table('konfigurasi_jk_dept_detail')
                    ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                    ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('kode_cabang', $kode_cabang)
                    ->where('hari', $namahari)
                    ->first();
            }
        }

        if ($jamkerja == null) {
            $jamkerja = DB::table('jam_kerja')->first();
        }

        return $jamkerja;
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        if (!$dataizin) {
            return Redirect::back()->with(['warning' => 'Data Izin Tidak Ditemukan']);
        }
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction();
        try {
            if ($status_approved == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {
                    $jamkerja = $this->getJamKerjaPegawai($nik, $tgl_dari);
                    $kode_jk = $jamkerja ? $jamkerja->kode_jam_kerja : 'JK01';

                    if ($status == 'it') {
                        // Izin Terlambat: Jam otomatis menjadi 1 menit sebelum jam masuk jadwal (contoh 07:59 jika jadwal 08:00)
                        $jam_masuk_jadwal = $jamkerja ? $jamkerja->jam_masuk : '08:00:00';
                        $jam_in_auto = date('H:i:s', strtotime('-1 minute', strtotime($jam_masuk_jadwal)));

                        $cekPresensi = DB::table('presensi')->where('tgl_presensi', $tgl_dari)->where('nik', $nik)->first();
                        if ($cekPresensi) {
                            DB::table('presensi')->where('id', $cekPresensi->id)->update([
                                'jam_in' => $jam_in_auto,
                                'kode_jam_kerja' => $kode_jk,
                                'status' => 'h',
                                'status_in' => 'izin_terlambat',
                                'selisih_in' => 0,
                                'kode_izin' => $kode_izin,
                                'foto_in' => $cekPresensi->foto_in ?? 'izin_terlambat.png',
                                'lokasi_in' => $cekPresensi->lokasi_in ?? 'Disetujui Izin Terlambat'
                            ]);
                        } else {
                            DB::table('presensi')->insert([
                                'nik' => $nik,
                                'tgl_presensi' => $tgl_dari,
                                'jam_in' => $jam_in_auto,
                                'kode_jam_kerja' => $kode_jk,
                                'status' => 'h',
                                'status_in' => 'izin_terlambat',
                                'status_out' => null,
                                'selisih_in' => 0,
                                'selisih_out' => 0,
                                'kode_izin' => $kode_izin,
                                'foto_in' => 'izin_terlambat.png',
                                'lokasi_in' => 'Disetujui Izin Terlambat'
                            ]);
                        }
                    } else if ($status == 'ipc') {
                        // Izin Pulang Cepat: Jam pulang otomatis menjadi jam pulang jadwal (contoh 16:00:00)
                        $jam_pulang_jadwal = $jamkerja ? $jamkerja->jam_pulang : '16:00:00';

                        $cekPresensi = DB::table('presensi')->where('tgl_presensi', $tgl_dari)->where('nik', $nik)->first();
                        if ($cekPresensi) {
                            DB::table('presensi')->where('id', $cekPresensi->id)->update([
                                'jam_out' => $jam_pulang_jadwal,
                                'kode_jam_kerja' => $kode_jk,
                                'status' => 'h',
                                'status_out' => 'izin_pulang_cepat',
                                'selisih_out' => 0,
                                'kode_izin' => $kode_izin,
                                'foto_out' => $cekPresensi->foto_out ?? 'izin_pulang_cepat.png',
                                'lokasi_out' => $cekPresensi->lokasi_out ?? 'Disetujui Izin Pulang Cepat'
                            ]);
                        } else {
                            DB::table('presensi')->insert([
                                'nik' => $nik,
                                'tgl_presensi' => $tgl_dari,
                                'jam_out' => $jam_pulang_jadwal,
                                'kode_jam_kerja' => $kode_jk,
                                'status' => 'h',
                                'status_in' => null,
                                'status_out' => 'izin_pulang_cepat',
                                'selisih_in' => 0,
                                'selisih_out' => 0,
                                'kode_izin' => $kode_izin,
                                'foto_out' => 'izin_pulang_cepat.png',
                                'lokasi_out' => 'Disetujui Izin Pulang Cepat'
                            ]);
                        }
                    } else {
                        // Izin Tidak Hadir (i), Sakit (s), Cuti (c)
                        DB::table('presensi')->insert([
                            'nik' => $nik,
                            'tgl_presensi' => $tgl_dari,
                            'status' => $status,
                            'status_in' => null,
                            'status_out' => null,
                            'kode_izin' => $kode_izin
                        ]);
                    }

                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            } else {
                // Jika status_approved == 2 (Ditolak) atau 0 (Pending/Batal), bersihkan presensi yang pernah digenerate
                $this->cleanupPresensiIzin($nik, $tgl_dari, $tgl_sampai, $status, $kode_izin);
            }

            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                'status_approved' => $status_approved
            ]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Diproses']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Diproses: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper untuk membersihkan data presensi otomatis saat izin dibatalkan / ditolak
     */
    private function cleanupPresensiIzin($nik, $tgl_dari, $tgl_sampai, $status, $kode_izin)
    {
        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereBetween('tgl_presensi', [$tgl_dari, $tgl_sampai])
            ->get();

        foreach ($presensi as $p) {
            if ($status == 'it') {
                // Cek apakah ada izin IPC lain yang masih aktif pada tanggal ini
                $otherIzin = DB::table('pengajuan_izin')
                    ->where('nik', $nik)
                    ->where('tgl_izin_dari', $p->tgl_presensi)
                    ->where('kode_izin', '!=', $kode_izin)
                    ->where('status', 'ipc')
                    ->where('status_approved', 1)
                    ->first();

                $hasRealOut = (!empty($p->jam_out) && $p->jam_out != 'Belum Absen' && $p->foto_out != 'izin_pulang_cepat.png');
                $hasActiveIpc = ($otherIzin != null);

                if (!$hasRealOut && !$hasActiveIpc) {
                    // Jika tidak ada data pulang sama sekali, hapus baris presensi sepenuhnya
                    DB::table('presensi')->where('id', $p->id)->delete();
                } else {
                    // Ada data pulang, maka hapus seluruh data masuk yang digenerate oleh izin terlambat (termasuk jam 07:59)
                    DB::table('presensi')->where('id', $p->id)->update([
                        'jam_in' => null,
                        'status_in' => null,
                        'selisih_in' => 0,
                        'foto_in' => null,
                        'lokasi_in' => null,
                        'kode_izin' => $otherIzin ? $otherIzin->kode_izin : null
                    ]);
                }
            } else if ($status == 'ipc') {
                // Cek apakah ada izin IT lain yang masih aktif pada tanggal ini
                $otherIzin = DB::table('pengajuan_izin')
                    ->where('nik', $nik)
                    ->where('tgl_izin_dari', $p->tgl_presensi)
                    ->where('kode_izin', '!=', $kode_izin)
                    ->where('status', 'it')
                    ->where('status_approved', 1)
                    ->first();

                $hasRealIn = (!empty($p->jam_in) && $p->jam_in != 'Belum Absen' && $p->foto_in != 'izin_terlambat.png');
                $hasActiveIt = ($otherIzin != null);

                if (!$hasRealIn && !$hasActiveIt) {
                    // Jika tidak ada data masuk sama sekali, hapus baris presensi sepenuhnya
                    DB::table('presensi')->where('id', $p->id)->delete();
                } else {
                    // Ada data masuk, maka hapus seluruh data pulang yang digenerate oleh izin pulang cepat
                    DB::table('presensi')->where('id', $p->id)->update([
                        'jam_out' => null,
                        'status_out' => null,
                        'selisih_out' => 0,
                        'foto_out' => null,
                        'lokasi_out' => null,
                        'kode_izin' => $otherIzin ? $otherIzin->kode_izin : null
                    ]);
                }
            } else {
                DB::table('presensi')->where('id', $p->id)->delete();
            }
        }
    }

    public function batalkanizinsakit($kode_izin)
    {
        DB::beginTransaction();
        try {
            $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
            if ($dataizin) {
                $this->cleanupPresensiIzin($dataizin->nik, $dataizin->tgl_izin_dari, $dataizin->tgl_izin_sampai, $dataizin->status, $kode_izin);

                DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
                    'status_approved' => 0
                ]);
            }
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Batalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Di Batalkan: ' . $e->getMessage()]);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }


    public function storefrommachine()
    {
        $original_data  = file_get_contents('php://input');
        $decoded_data   = json_decode($original_data, true);
        $encoded_data   = json_encode($decoded_data);

        $data           = $decoded_data['data'];
        $pin            = $data['pin'];

        DB::table('presensi')->insert([
            'nik' => $pin,
            'tgl_presensi' => '2023-05-03'
        ]);
    }

    public function showact($kode_izin)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->join('pegawai', 'pengajuan_izin.nik', '=', 'pegawai.nik')
            ->select('pengajuan_izin.*', 'master_cuti.nama_cuti', 'pegawai.nama_lengkap')
            ->where('pengajuan_izin.kode_izin', $kode_izin)
            ->where('pengajuan_izin.nik', $nik)
            ->first();

        if (!$dataizin) {
            return '<div class="alert alert-danger p-2 m-2">Data tidak ditemukan atau bukan milik Anda.</div>';
        }
        return view('presensi.showact', compact('dataizin'));
    }

    public function penerimaanact($kode_penerimaan)
    {
        $datapenerimaan = DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->first();
        return view('presensi.penerimaanact', compact('datapenerimaan'));
    }


    public function deleteizin($kode_izin)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->where('nik', $nik)->first();
        if (!$cekdataizin) {
            return redirect('/presensi/izin')->with(['error' => 'Data izin tidak ditemukan atau bukan milik Anda']);
        }
        if (isset($cekdataizin->status_approved) && $cekdataizin->status_approved == 1) {
            return redirect('/presensi/izin')->with(['error' => 'Izin yang sudah disetujui tidak dapat dihapus']);
        }
        $doc_sid = $cekdataizin->doc_sid;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->where('nik', $nik)->delete();
            if ($doc_sid != null) {
                Storage::delete('/public/uploads/sid/' . $doc_sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Dihapus']);
        }
    }

    public function deletepenerimaan($kode_penerimaan)
    {
        $cekdatapenerimaan = DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->first();
        $doc_sid = $cekdatapenerimaan->doc_sid;

        try {
            DB::table('penerimaan')->where('kode_penerimaan', $kode_penerimaan)->delete();
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

    public function koreksipresensi(Request $request)
    {
        $nik = $request->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $tanggal)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('presensi.koreksipresensi', compact('karyawan', 'tanggal', 'jamkerja', 'presensi'));
    }


    public function storekoreksipresensi(Request $request)
    {
        $status = $request->status;
        $nik = $request->nik;
        $tanggal = $request->tanggal;
        $isHadir = ($status === 'h');
        $jam_in = $isHadir ? $request->jam_in : null;
        $jam_out = $isHadir ? $request->jam_out : null;
        $kode_jam_kerja = $isHadir ? $request->kode_jam_kerja : null;

        try {
            $cekpresensi = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $tanggal)->count();
            if ($cekpresensi > 0) {
                DB::table('presensi')
                    ->where('nik', $nik)
                    ->where('tgl_presensi', $tanggal)
                    ->update([
                        'jam_in' => $jam_in,
                        'jam_out' => $jam_out,
                        'kode_jam_kerja' => $kode_jam_kerja,
                        'status' => $status
                    ]);
            } else {
                DB::table('presensi')->insert([
                    'nik' => $nik,
                    'tgl_presensi' => $tanggal,
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                    'kode_jam_kerja' => $kode_jam_kerja,
                    'status' => $status
                ]);
            }

            return Redirect::back()->with(['success' => 'Data presensi berhasil disimpan', 'tgl_presensi' => $tanggal]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error storekoreksipresensi: ' . $e->getMessage());
            return Redirect::back()->with(['warning' => 'Data presensi gagal disimpan']);
        }
    }

    public function pilihjamkerja()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
        $hariini = date('Y-m-d');

        // Cek apakah hari ini adalah Hari Libur
        $datalibur = DB::table('harilibur')
            ->leftJoin('harilibur_detail', 'harilibur.kode_libur', '=', 'harilibur_detail.kode_libur')
            ->where('harilibur.tanggal_libur', $hariini)
            ->where(function ($query) use ($nik, $kode_cabang) {
                $query->where('harilibur_detail.nik', $nik)
                    ->orWhere('harilibur.kode_cabang', 'ALL')
                    ->orWhere('harilibur.kode_cabang', $kode_cabang);
            })
            ->select('harilibur.kode_libur', 'harilibur.tanggal_libur', 'harilibur.keterangan')
            ->first();

        $cekpresensi = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        if (!$datalibur && $cekpresensi != null && $cekpresensi->status == 'l') {
            $izin = DB::table('pengajuan_izin')->where('kode_izin', $cekpresensi->kode_izin)->first();
            $datalibur = (object)[
                'kode_libur' => 'LIBUR',
                'tanggal_libur' => $hariini,
                'keterangan' => ($izin && !empty($izin->keterangan)) ? $izin->keterangan : 'Libur Nasional / Bersama'
            ];
        }

        if ($datalibur != null) {
            return view('presensi.notiflibur', compact('datalibur'));
        }

        if (!empty($cekpresensi)) {
            $kode_jam_kerja = Crypt::encrypt($cekpresensi->kode_jam_kerja);
            return redirect('/presensi/' . $kode_jam_kerja . '/create');
        }
        $jamkerja = DB::table('jam_kerja')->get();
        return view('presensi.pilihjamkerja', compact('jamkerja'));
    }





















    // public function scanqr()
    // {
    //     $hariini = date("Y-m-d");
    //     $namahari = $this->gethari();
    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
    //     $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
    //     $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
    //     $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
    //     $jamkerja = DB::table('konfigurasi_jamkerja')
    //         ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //         ->where('nik', $nik)->where('hari', $namahari)->first();

    //     if ($jamkerja == null) {
    //         $jamkerja = DB::table('konfigurasi_jk_dept_detail')
    //             ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
    //             ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //             ->where('kode_dept', $kode_dept)
    //             ->where('kode_cabang', $kode_cabang)
    //             ->where('hari', $namahari)->first();
    //     }
    //     return view('presensi.scanqr', compact('cek', 'lok_kantor', 'jamkerja'));
    // }


    // public function storeqr(Request $request)
    // {

    //     $nik = Auth::guard('karyawan')->user()->nik;
    //     $kode_cabang = Auth::guard('karyawan')->user()->kode_cabang;
    //     $kode_dept = Auth::guard('karyawan')->user()->kode_dept;
    //     $qrcode = $request->qrcode;
    //     $tgl_presensi = date("Y-m-d");
    //     $jam = date("H:i:s");
    //     $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
    //     $lok = explode(",", $lok_kantor->lokasi_cabang);
    //     $latitudekantor = $lok[0];
    //     $longitudekantor = $lok[1];
    //     $lokasi = $request->lokasi;
    //     $lokasiuser = explode(",", $lokasi);
    //     $latitudeuser = $lokasiuser[0];
    //     $longitudeuser = $lokasiuser[1];

    //     $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
    //     $radius = round($jarak["meters"]);

    //     //Cek Jam Kerja Karyawan
    //     $namahari = $this->gethari();
    //     $jamkerja = DB::table('konfigurasi_jamkerja')
    //         ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //         ->where('nik', $nik)->where('hari', $namahari)->first();

    //     if ($jamkerja == null) {
    //         $jamkerja = DB::table('konfigurasi_jk_dept_detail')
    //             ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
    //             ->join('jam_kerja', 'konfigurasi_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
    //             ->where('kode_dept', $kode_dept)
    //             ->where('kode_cabang', $kode_cabang)
    //             ->where('hari', $namahari)->first();
    //     }

    //     $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();


    //     // echo $cek;
    //     // die;
    //     if ($cek > 0) {
    //         $ket = "out";
    //     } else {
    //         $ket = "in";
    //     }

    //     $image = $request->image;
    //     if (!empty($image)) {
    //         $folderPath = "public/uploads/absensi/";
    //         $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
    //         $image_parts = explode(";base64", $image);
    //         $image_base64 = base64_decode($image_parts[1]);
    //         $fileName = $formatName . ".png";
    //         $file = $folderPath . $fileName;
    //     } else {
    //         $fileName = NULL;
    //     }

    //     $datakaryawan = DB::table('karyawan')->where('nik', $nik)->first();
    //     $no_hp = $datakaryawan->no_hp;

    //     $kode_validasi = "adamadifa";
    //     if ($qrcode != $kode_validasi) {
    //         echo "error|Kode QR Tidak Valid|validasiqr";
    //     } else {
    //         if ($radius > $lok_kantor->radius_cabang) {
    //             echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor|radius";
    //         } else {
    //             if ($cek > 0) {
    //                 if ($jam < $jamkerja->jam_pulang) {
    //                     echo "error|Maaf Belum Waktunya Pulang |out";
    //                 } else {
    //                     $data_pulang = [
    //                         'jam_out' => $jam,
    //                         'foto_out' => $fileName,
    //                         'lokasi_out' => $lokasi
    //                     ];
    //                     $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
    //                     if ($update) {
    //                         echo "success|Terimkasih, Hati Hati Di Jalan|out";
    //                     } else {
    //                         echo "error|Maaf Gagal absen, Hubungi Tim It|out";
    //                     }
    //                 }
    //             } else {
    //                 if ($jam < $jamkerja->awal_jam_masuk) {
    //                     echo "error|Maaf Belum Waktunya Melakuan Presensi|in";
    //                 } else if ($jam > $jamkerja->akhir_jam_masuk) {
    //                     echo "error|Maaf Waktu Untuk Presensi Sudah Habis |in";
    //                 } else {
    //                     $data = [
    //                         'nik' => $nik,
    //                         'tgl_presensi' => $tgl_presensi,
    //                         'jam_in' => $jam,
    //                         'foto_in' => $fileName,
    //                         'lokasi_in' => $lokasi,
    //                         'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
    //                         'status' => 'h'
    //                     ];
    //                     $simpan = DB::table('presensi')->insert($data);
    //                     if ($simpan) {
    //                         echo "success|Terimkasih, Selamat Bekerja|in";
    //                     } else {
    //                         echo "error|Maaf Gagal absen, Hubungi Tim It|in";
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    public function statuskehadiran(Request $request)
    {
        $user = Auth::guard('user')->user();
        $kode_dept = $user ? $user->kode_dept : null;
        $kode_cabang = $user ? $user->kode_cabang : null;

        $dari = $request->dari ?? date('Y-m-01');
        $sampai = $request->sampai ?? date('Y-m-d');

        $query = DB::table('presensi')
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
            ->whereBetween('presensi.tgl_presensi', [$dari, $sampai]);

        if (!empty($request->nama_pegawai)) {
            $query->where(function ($q) use ($request) {
                $q->where('pegawai.nama_lengkap', 'like', '%' . $request->nama_pegawai . '%')
                  ->orWhere('pegawai.nik', 'like', '%' . $request->nama_pegawai . '%');
            });
        }

        if (!empty($request->kode_dept)) {
            $query->where('pegawai.kode_dept', $request->kode_dept);
        }

        if (!empty($request->kode_cabang)) {
            $query->where('pegawai.kode_cabang', $request->kode_cabang);
        }

        if ($request->filled('status_filter')) {
            $sf = $request->status_filter;
            if ($sf == 'h') {
                $query->where('presensi.status', 'h')->where(function ($q) {
                    $q->where('presensi.status_in', 'tepat_waktu')
                      ->orWhereNull('presensi.status_in');
                })->where(function ($q) {
                    $q->where('presensi.status_out', 'tepat_waktu')
                      ->orWhereNull('presensi.status_out');
                });
            } elseif ($sf == 'terlambat') {
                $query->where('presensi.status_in', 'terlambat');
            } elseif ($sf == 'pulang_duluan') {
                $query->where('presensi.status_out', 'pulang_duluan');
            } elseif ($sf == 'it') {
                $query->where('presensi.status_in', 'izin_terlambat');
            } elseif ($sf == 'ipc') {
                $query->where('presensi.status_out', 'izin_pulang_cepat');
            } else {
                $query->where('presensi.status', $sf);
            }
        }

        if ($user && $user->hasRole('admin departemen')) {
            $query->where('pegawai.kode_cabang', $kode_cabang);
            $query->where('pegawai.kode_dept', $kode_dept);
        }

        $query->orderBy('presensi.tgl_presensi', 'desc');
        $query->orderBy('pegawai.nama_lengkap', 'asc');
        $datapresensi = $query->paginate(15);
        $datapresensi->appends($request->all());

        // Daftar pegawai untuk modal tambah status
        $queryPegawai = DB::table('pegawai')->orderBy('nama_lengkap', 'asc');
        if ($user && $user->hasRole('admin departemen')) {
            $queryPegawai->where('kode_cabang', $kode_cabang)->where('kode_dept', $kode_dept);
        }
        $listPegawai = $queryPegawai->get();

        $departemen = DB::table('seksi')->orderBy('nama_dept', 'asc')->get();
        $cabang = DB::table('cabang')->orderBy('nama_cabang', 'asc')->get();

        return view('presensi.statuskehadiran', compact('datapresensi', 'listPegawai', 'departemen', 'cabang', 'dari', 'sampai'));
    }

    public function storestatuskehadiran(Request $request)
    {
        $nik = $request->nik;
        $tgl_dari = $request->tgl_dari;
        $tgl_sampai = $request->tgl_sampai ?: $tgl_dari;
        $status_pilihan = $request->status_pilihan;
        $keterangan = $request->keterangan;

        if (empty($nik) || empty($tgl_dari) || empty($status_pilihan)) {
            return Redirect::back()->with(['warning' => 'Data NIK, Tanggal, dan Status Kehadiran wajib diisi.']);
        }

        DB::beginTransaction();
        try {
            $cur_tgl = $tgl_dari;
            while (strtotime($cur_tgl) <= strtotime($tgl_sampai)) {
                $jamkerja = $this->getJamKerjaPegawai($nik, $cur_tgl);
                $jam_masuk_jadwal = $jamkerja ? $jamkerja->jam_masuk : '08:00:00';
                $jam_pulang_jadwal = $jamkerja ? $jamkerja->jam_pulang : '16:00:00';
                $kode_jk = $jamkerja ? $jamkerja->kode_jam_kerja : 'JK01';

                $status = 'h';
                $status_in = 'tepat_waktu';
                $status_out = 'tepat_waktu';
                $selisih_in = 0;
                $selisih_out = 0;
                $jam_in = null;
                $jam_out = null;
                $kode_izin = null;

                if ($status_pilihan == 'h') {
                    $status = 'h';
                    $jam_in = !empty($request->jam_in) ? $request->jam_in : $jam_masuk_jadwal;
                    $jam_out = !empty($request->jam_out) ? $request->jam_out : $jam_pulang_jadwal;

                    // Otomatis hitung status terlambat jika jam masuk melebihi jam masuk jadwal
                    if (!empty($jam_in) && $jam_in > $jam_masuk_jadwal) {
                        $status_in = 'terlambat';
                        $pMasuk = explode(':', $jam_masuk_jadwal);
                        $pIn = explode(':', $jam_in);
                        $tMasuk = mktime((int)$pMasuk[0], (int)$pMasuk[1], (int)($pMasuk[2] ?? 0), 1, 1, 1);
                        $tIn = mktime((int)$pIn[0], (int)$pIn[1], (int)($pIn[2] ?? 0), 1, 1, 1);
                        $diffSec = $tIn - $tMasuk;
                        $selisih_in = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
                    } else {
                        $status_in = 'tepat_waktu';
                        $selisih_in = 0;
                    }

                    // Otomatis hitung status pulang duluan jika jam pulang kurang dari jam pulang jadwal
                    if (!empty($jam_out) && $jam_out < $jam_pulang_jadwal) {
                        $status_out = 'pulang_duluan';
                        $pPulang = explode(':', $jam_pulang_jadwal);
                        $pOut = explode(':', $jam_out);
                        $tPulang = mktime((int)$pPulang[0], (int)$pPulang[1], (int)($pPulang[2] ?? 0), 1, 1, 1);
                        $tOut = mktime((int)$pOut[0], (int)$pOut[1], (int)($pOut[2] ?? 0), 1, 1, 1);
                        $diffSec = $tPulang - $tOut;
                        $selisih_out = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
                    } else {
                        $status_out = 'tepat_waktu';
                        $selisih_out = 0;
                    }
                } elseif ($status_pilihan == 'it') {
                    $status = 'h';
                    $status_in = 'izin_terlambat';
                    $status_out = 'tepat_waktu';
                    $jam_in = date('H:i:s', strtotime('-1 minute', strtotime($jam_masuk_jadwal)));
                    $jam_out = !empty($request->jam_out) ? $request->jam_out : $jam_pulang_jadwal;
                } elseif ($status_pilihan == 'ipc') {
                    $status = 'h';
                    $status_in = 'tepat_waktu';
                    $status_out = 'izin_pulang_cepat';
                    $jam_in = !empty($request->jam_in) ? $request->jam_in : $jam_masuk_jadwal;
                    $jam_out = $jam_pulang_jadwal;
                } else {
                    $status = $status_pilihan;
                    $status_in = null;
                    $status_out = null;
                    $jam_in = null;
                    $jam_out = null;
                }

                if (in_array($status_pilihan, ['i', 's', 'c', 'it', 'ipc', 'tl', 'l'])) {
                    $bulan = date("m", strtotime($cur_tgl));
                    $tahun = date("Y", strtotime($cur_tgl));
                    $thn = substr($tahun, 2, 2);
                    $lastizin = DB::table('pengajuan_izin')
                        ->whereRaw('MONTH(tgl_izin_dari)="' . $bulan . '"')
                        ->whereRaw('YEAR(tgl_izin_dari)="' . $tahun . '"')
                        ->orderBy('kode_izin', 'desc')
                        ->first();
                    $lastkodeizin = $lastizin ? $lastizin->kode_izin : "";
                    $format = "IZ" . $bulan . $thn;
                    $kode_izin = buatkode($lastkodeizin, $format, 3);

                    DB::table('pengajuan_izin')->insert([
                        'kode_izin' => $kode_izin,
                        'nik' => $nik,
                        'tgl_izin_dari' => $cur_tgl,
                        'tgl_izin_sampai' => $cur_tgl,
                        'status' => $status_pilihan,
                        'keterangan' => !empty($keterangan) ? $keterangan : ($status_pilihan == 'tl' ? 'Tugas Luar' : ($status_pilihan == 'l' ? 'Libur Nasional' : 'Ditetapkan oleh Admin')),
                        'status_approved' => '1'
                    ]);
                }

                $cek = DB::table('presensi')->where('tgl_presensi', $cur_tgl)->where('nik', $nik)->first();
                if ($cek) {
                    $updateData = [
                        'kode_jam_kerja' => $kode_jk,
                        'status' => $status,
                        'status_in' => $status_in,
                        'status_out' => $status_out,
                        'selisih_in' => $selisih_in,
                        'selisih_out' => $selisih_out,
                        'jam_in' => $jam_in,
                        'jam_out' => $jam_out,
                        'lokasi_in' => $cek->lokasi_in ?: 'Ditetapkan oleh Admin',
                        'foto_in' => $cek->foto_in ?: 'admin_set.png'
                    ];
                    if ($kode_izin) {
                        $updateData['kode_izin'] = $kode_izin;
                    }
                    DB::table('presensi')->where('id', $cek->id)->update($updateData);
                } else {
                    DB::table('presensi')->insert([
                        'nik' => $nik,
                        'tgl_presensi' => $cur_tgl,
                        'jam_in' => $jam_in,
                        'jam_out' => $jam_out,
                        'foto_in' => 'admin_set.png',
                        'foto_out' => !empty($jam_out) ? 'admin_set.png' : null,
                        'lokasi_in' => 'Ditetapkan oleh Admin',
                        'lokasi_out' => !empty($jam_out) ? 'Ditetapkan oleh Admin' : null,
                        'kode_jam_kerja' => $kode_jk,
                        'status' => $status,
                        'status_in' => $status_in,
                        'status_out' => $status_out,
                        'selisih_in' => $selisih_in,
                        'selisih_out' => $selisih_out,
                        'kode_izin' => $kode_izin
                    ]);
                }

                $cur_tgl = date('Y-m-d', strtotime('+1 day', strtotime($cur_tgl)));
            }

            DB::commit();
            return Redirect::back()->with(['success' => 'Status kehadiran pegawai berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Gagal menyimpan status kehadiran: ' . $e->getMessage()]);
        }
    }

    public function editstatuskehadiran(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'pegawai.nama_lengkap', 'pengajuan_izin.keterangan as keterangan_izin')
            ->join('pegawai', 'presensi.nik', '=', 'pegawai.nik')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->where('presensi.id', $id)
            ->first();

        return response()->json($presensi);
    }

    public function updatestatuskehadiran(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)->first();
        if (!$presensi) {
            return Redirect::back()->with(['warning' => 'Data presensi tidak ditemukan.']);
        }

        $nik = $presensi->nik;
        $cur_tgl = $presensi->tgl_presensi;
        $status_pilihan = $request->status_pilihan;
        $keterangan = $request->keterangan;

        $jamkerja = $this->getJamKerjaPegawai($nik, $cur_tgl);
        $jam_masuk_jadwal = $jamkerja ? $jamkerja->jam_masuk : '08:00:00';
        $jam_pulang_jadwal = $jamkerja ? $jamkerja->jam_pulang : '16:00:00';

        $status = 'h';
        $status_in = 'tepat_waktu';
        $status_out = 'tepat_waktu';
        $selisih_in = 0;
        $selisih_out = 0;
        $jam_in = null;
        $jam_out = null;

        if ($status_pilihan == 'h') {
            $status = 'h';
            $jam_in = !empty($request->jam_in) ? $request->jam_in : ($presensi->jam_in ?: $jam_masuk_jadwal);
            $jam_out = !empty($request->jam_out) ? $request->jam_out : ($presensi->jam_out ?: $jam_pulang_jadwal);

            // Otomatis hitung status terlambat jika jam masuk melebihi jam masuk jadwal
            if (!empty($jam_in) && $jam_in > $jam_masuk_jadwal) {
                $status_in = 'terlambat';
                $pMasuk = explode(':', $jam_masuk_jadwal);
                $pIn = explode(':', $jam_in);
                $tMasuk = mktime((int)$pMasuk[0], (int)$pMasuk[1], (int)($pMasuk[2] ?? 0), 1, 1, 1);
                $tIn = mktime((int)$pIn[0], (int)$pIn[1], (int)($pIn[2] ?? 0), 1, 1, 1);
                $diffSec = $tIn - $tMasuk;
                $selisih_in = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
            } else {
                $status_in = 'tepat_waktu';
                $selisih_in = 0;
            }

            // Otomatis hitung status pulang duluan jika jam pulang kurang dari jam pulang jadwal
            if (!empty($jam_out) && $jam_out < $jam_pulang_jadwal) {
                $status_out = 'pulang_duluan';
                $pPulang = explode(':', $jam_pulang_jadwal);
                $pOut = explode(':', $jam_out);
                $tPulang = mktime((int)$pPulang[0], (int)$pPulang[1], (int)($pPulang[2] ?? 0), 1, 1, 1);
                $tOut = mktime((int)$pOut[0], (int)$pOut[1], (int)($pOut[2] ?? 0), 1, 1, 1);
                $diffSec = $tPulang - $tOut;
                $selisih_out = $diffSec > 0 ? (int)floor($diffSec / 60) : 0;
            } else {
                $status_out = 'tepat_waktu';
                $selisih_out = 0;
            }
        } elseif ($status_pilihan == 'it') {
            $status = 'h';
            $status_in = 'izin_terlambat';
            $jam_in = date('H:i:s', strtotime('-1 minute', strtotime($jam_masuk_jadwal)));
            $jam_out = !empty($request->jam_out) ? $request->jam_out : ($presensi->status_out == 'izin_pulang_cepat' ? $presensi->jam_out : ($presensi->foto_out == 'izin_pulang_cepat.png' ? null : $presensi->jam_out));
        } elseif ($status_pilihan == 'ipc') {
            $status = 'h';
            $status_out = 'izin_pulang_cepat';
            $jam_in = !empty($request->jam_in) ? $request->jam_in : ($presensi->status_in == 'izin_terlambat' ? null : ($presensi->foto_in == 'izin_terlambat.png' ? null : $presensi->jam_in));
            $jam_out = $jam_pulang_jadwal;
        } else {
            $status = $status_pilihan;
            $status_in = null;
            $status_out = null;
            $jam_in = null;
            $jam_out = null;
        }

        $updatePresensi = [
            'status' => $status,
            'status_in' => $status_in,
            'status_out' => $status_out,
            'jam_in' => $jam_in,
            'jam_out' => $jam_out
        ];

        if (in_array($status_pilihan, ['i', 's', 'c', 'it', 'ipc', 'tl', 'l'])) {
            if (!empty($presensi->kode_izin)) {
                $updIzin = ['status' => $status_pilihan];
                if (!empty($keterangan)) {
                    $updIzin['keterangan'] = $keterangan;
                } elseif ($status_pilihan == 'tl') {
                    $updIzin['keterangan'] = 'Tugas Luar';
                } elseif ($status_pilihan == 'l') {
                    $updIzin['keterangan'] = 'Libur Nasional';
                }
                DB::table('pengajuan_izin')->where('kode_izin', $presensi->kode_izin)->update($updIzin);
            } else {
                $bulan = date("m", strtotime($cur_tgl));
                $tahun = date("Y", strtotime($cur_tgl));
                $thn = substr($tahun, 2, 2);
                $lastizin = DB::table('pengajuan_izin')
                    ->whereRaw('MONTH(tgl_izin_dari)="' . $bulan . '"')
                    ->whereRaw('YEAR(tgl_izin_dari)="' . $tahun . '"')
                    ->orderBy('kode_izin', 'desc')
                    ->first();
                $lastkodeizin = $lastizin ? $lastizin->kode_izin : "";
                $format = "IZ" . $bulan . $thn;
                $kode_izin = buatkode($lastkodeizin, $format, 3);

                DB::table('pengajuan_izin')->insert([
                    'kode_izin' => $kode_izin,
                    'nik' => $nik,
                    'tgl_izin_dari' => $cur_tgl,
                    'tgl_izin_sampai' => $cur_tgl,
                    'status' => $status_pilihan,
                    'keterangan' => !empty($keterangan) ? $keterangan : ($status_pilihan == 'tl' ? 'Tugas Luar' : ($status_pilihan == 'l' ? 'Libur Nasional' : 'Ditetapkan oleh Admin')),
                    'status_approved' => '1'
                ]);

                $updatePresensi['kode_izin'] = $kode_izin;
            }
        }

        DB::table('presensi')->where('id', $id)->update($updatePresensi);

        return Redirect::back()->with(['success' => 'Status kehadiran berhasil diperbarui.']);
    }

    public function deletestatuskehadiran($id)
    {
        $presensi = DB::table('presensi')->where('id', $id)->first();
        if ($presensi) {
            if (!empty($presensi->kode_izin)) {
                DB::table('pengajuan_izin')->where('kode_izin', $presensi->kode_izin)->delete();
            }
            DB::table('presensi')->where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data presensi berhasil dihapus.']);
        }
        return Redirect::back()->with(['warning' => 'Data presensi tidak ditemukan.']);
    }
}
