<?php

use Illuminate\Support\Facades\DB;

function hitungjamterlambat($jadwal_jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


    $terlambat = $jterlambat . ":" . $mterlambat;
    return $terlambat;
}


function hitungjamterlambatdesimal($jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;


    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);
    return  $desimalterlambat;
}



function hitunghari($tanggal_mulai, $tanggal_akhir)
{
    $tanggal_1 = date_create($tanggal_mulai);
    $tanggal_2 = date_create($tanggal_akhir); // waktu sekarang
    $diff = date_diff($tanggal_1, $tanggal_2);
    return $diff->days + 1;
}


function hitungdenda($jam_terlambat)
{
    $j_terlambat = explode(":", $jam_terlambat);
    $jam = intval($j_terlambat[0]) * 1;
    $menit = intval($j_terlambat[1]) * 1;

    /*
        Terlambat < 5 Menit = 0;
        Terlambat 5 - 9 Menit = 5000;
        Terlambat 10 - 14 Menit = 10000;
        Terlambat 15 - 59 Menit = 15000;
        Terlambat > 1 Jam = Upah Perjam ( Potongan Jam Kerja)
    */

    if ($jam < 1) {
        if ($menit >= 5 && $menit < 10) {
            $denda = 5000;
        } else if ($menit >= 10 && $menit < 15) {
            $denda = 15000;
        } else if ($menit >= 15) {
            $denda = 15000;
        } else {
            $denda = 0;
        }
    } else {
        $denda = 0;
    }

    return $denda;
}


function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
{
    /* mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
    string nomor baru dibawah ini harus dengan format XXX000000
    untuk penggunaan dalam format lain anda harus menyesuaikan sendiri */
    $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
    //    menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
    $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
    //    menyusun kunci dan nomor baru
    $kode = $kunci . $nomor_baru_plus_nol;
    return $kode;
}


function hitungjamkerja($tgl_presensi, $jam_mulai, $jam_berakhir, $max_total_jam, $lintashari, $jam_awal_istirahat, $jam_akhir_istirahat)
{

    if ($lintashari == '1') {
        $tgl_pulang = date('Y-m-d', strtotime("+1 days", strtotime($tgl_presensi)));
    } else {
        $tgl_pulang = $tgl_presensi;
    }
    $jam_mulai = $tgl_presensi . " " . $jam_mulai;
    $jam_pulang = !empty($jam_berakhir) ? $tgl_pulang . " " . $jam_berakhir : "";

    if ($jam_awal_istirahat != "NA") {
        $jam_awal_istirahat = $tgl_pulang . " " . $jam_awal_istirahat;
        $jam_akhir_istirahat = $tgl_pulang . " " . $jam_akhir_istirahat;
        if ($jam_pulang > $jam_awal_istirahat && $jam_pulang <= $jam_akhir_istirahat) {
            $jam_pulang = $jam_awal_istirahat;
        }
    }

    $j_mulai = strtotime($jam_mulai);
    $j_pulang = strtotime($jam_pulang);
    $diff = $j_pulang - $j_mulai;
    if (empty($j_pulang)) {
        $jam = 0;
        $menit = 0;
    } else {
        $jam = floor($diff / (60 * 60)); //DIkurang 1 Jam jam Istirahat
        $m = $diff - $jam * (60 * 60);
        $menit = floor($m / 60);
    }

    $menitdesimal = ROUND($menit / 60, 2);
    /*
    Jika Karyawan Pulang Setelah Jam Isitrahat Maka Total Jam Kerja dikurang 1 Jam  Jika
    Kurang dari Jam Istirahat Tidak DI kurangi 1 Jam */
    if ($jam_awal_istirahat != "NA") {
        if ($jam_pulang > $jam_akhir_istirahat) {
            $jam_istirahat = 1;
        } else {
            $jam_istirahat = 0;
        }
    } else {
        $jam_istirahat = 0;
    }

    $jamdesimal = $jam - $jam_istirahat  + $menitdesimal;
    $totaljam = $jamdesimal > $max_total_jam ? $max_total_jam : $jamdesimal;
    return  empty($jam_pulang) ? 0 : $totaljam;
}


function getkaryawanlibur($dari, $sampai)
{
    $harilibur = DB::table('harilibur')
        ->whereBetween('tanggal_libur', [$dari, $sampai])
        ->get();

    if ($harilibur->isEmpty()) {
        return [];
    }

    $allPegawai = DB::table('pegawai')->select('nik')->get();
    $karyawanlibur = [];
    $assigned = [];

    // Ambil detail yang sudah ada di database jika ada
    $datalibur = DB::table('harilibur_detail')
        ->join('harilibur', 'harilibur_detail.kode_libur', '=', 'harilibur.kode_libur')
        ->whereBetween('tanggal_libur', [$dari, $sampai])
        ->get();

    foreach ($datalibur as $d) {
        $assigned[$d->kode_libur . '_' . $d->nik] = true;
        $karyawanlibur[] = [
            'nik' => $d->nik,
            'tanggal_libur' => $d->tanggal_libur,
            'keterangan' => $d->keterangan
        ];
    }

    // Pastikan setiap hari libur berlaku untuk seluruh pegawai
    foreach ($harilibur as $hl) {
        foreach ($allPegawai as $p) {
            $key = $hl->kode_libur . '_' . $p->nik;
            if (!isset($assigned[$key])) {
                $assigned[$key] = true;
                $karyawanlibur[] = [
                    'nik' => $p->nik,
                    'tanggal_libur' => $hl->tanggal_libur,
                    'keterangan' => $hl->keterangan
                ];
            }
        }
    }

    return $karyawanlibur;
}


function cekkaryawanlibur($array, $search_list)
{

    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value) {

        // Iterate over each search condition
        foreach ($search_list as $k => $v) {

            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v) {

                // Skip two loops
                continue 2;
            }
        }

        // Append array element's key to the
        //result array
        $result[] = $value;
    }

    // Return result
    return $result;
}


function gethari($hari)
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

function cekJadwalKerja($nik, $kode_cabang, $kode_dept, $tanggal, $hari, $jadwal_by_date = null, $jadwal_pegawai = null, $jadwal_dept = null)
{
    // 1. Cek Jadwal Khusus per Tanggal
    if ($jadwal_by_date && isset($jadwal_by_date[$nik])) {
        $byDate = $jadwal_by_date[$nik]->firstWhere('tanggal', $tanggal);
        if ($byDate) {
            return !empty($byDate->kode_jam_kerja);
        }
    }

    // 2. Cek Jadwal Harian Khusus Pegawai
    if ($jadwal_pegawai && isset($jadwal_pegawai[$nik])) {
        $byHari = $jadwal_pegawai[$nik]->firstWhere('hari', $hari);
        if ($byHari) {
            return !empty($byHari->kode_jam_kerja);
        }
    }

    // 3. Cek Jadwal Kantor / Departemen
    if ($jadwal_dept && !empty($kode_cabang)) {
        if (!empty($kode_dept)) {
            $deptKey = $kode_cabang . '_' . $kode_dept;
            if (isset($jadwal_dept[$deptKey])) {
                $byDept = $jadwal_dept[$deptKey]->firstWhere('hari', $hari);
                if ($byDept) {
                    return !empty($byDept->kode_jam_kerja);
                }
            }
        }

        // Fallback: jika seksi belum diset, cari jadwal seksi lain di cabang/kantor yang sama
        foreach ($jadwal_dept as $key => $items) {
            if (str_starts_with($key, $kode_cabang . '_')) {
                $byDept = $items->firstWhere('hari', $hari);
                if ($byDept) {
                    return !empty($byDept->kode_jam_kerja);
                }
            }
        }
    }

    // Jika koleksi preload tidak diberikan, query langsung DB
    if (!$jadwal_by_date && !$jadwal_pegawai && !$jadwal_dept) {
        $byDate = DB::table('konfigurasi_jamkerja_by_date')
            ->where('nik', $nik)
            ->where('tanggal', $tanggal)
            ->first();
        if ($byDate) {
            return !empty($byDate->kode_jam_kerja);
        }

        $byHari = DB::table('konfigurasi_jamkerja')
            ->where('nik', $nik)
            ->where('hari', $hari)
            ->first();
        if ($byHari) {
            return !empty($byHari->kode_jam_kerja);
        }

        if (!empty($kode_cabang) && !empty($kode_dept)) {
            $byDept = DB::table('konfigurasi_jk_dept_detail')
                ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                ->where('kode_dept', $kode_dept)
                ->where('kode_cabang', $kode_cabang)
                ->where('hari', $hari)
                ->first();
            if ($byDept) {
                return !empty($byDept->kode_jam_kerja);
            }
        }

        // Fallback cek cabang yang sama
        if (!empty($kode_cabang)) {
            $byDept = DB::table('konfigurasi_jk_dept_detail')
                ->join('konfigurasi_jk_dept', 'konfigurasi_jk_dept_detail.kode_jk_dept', '=', 'konfigurasi_jk_dept.kode_jk_dept')
                ->where('kode_cabang', $kode_cabang)
                ->where('hari', $hari)
                ->first();
            if ($byDept) {
                return !empty($byDept->kode_jam_kerja);
            }
        }
    }

    // 4. Default: Minggu dan Sabtu libur jika tidak ada jadwal yang ditentukan
    if ($hari == 'Minggu' || $hari == 'Sabtu') {
        return false;
    }

    return true;
}
