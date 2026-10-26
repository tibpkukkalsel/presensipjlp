<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Presensi - {{ $karyawan->nama_lengkap ?? '' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 6px 4px;
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        if (!function_exists('tgl_indo')) {
            function tgl_indo($tanggal)
            {
                $bulan = [
                    1 => 'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember',
                ];
                $pecahkan = explode('-', $tanggal);
                return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
            }
        }

        $kota_cetak = $konfigurasi_laporan->kota_cetak ?? 'Pelaihari';
        $pejabat1_jabatan = $konfigurasi_laporan->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL';
        $pejabat1_nama = $konfigurasi_laporan->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM';
        $pejabat1_nip = $konfigurasi_laporan->pejabat1_nip ?? '19870222 200701 1 002';
        $pejabat2_jabatan = $konfigurasi_laporan->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA';
        $pejabat2_nama = $konfigurasi_laporan->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST';
        $pejabat2_nip = $konfigurasi_laporan->pejabat2_nip ?? '19800813 200712 1 001';
    @endphp

    <table style="width: 100%;">
        <tr>
            <th colspan="9" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN PRESENSI BPKUK PROV. KALSEL</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 12pt; font-weight: bold; text-align: center;">PENYEDIA JASA LAINNYA PERORANGAN</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 11pt; font-weight: bold; text-align: center;">BULAN {{ strtoupper($namabulan[(int) $bulan] ?? '') }} TAHUN {{ $tahun }}</th>
        </tr>
        <tr>
            <td colspan="9" style="height: 15px;"></td>
        </tr>
    </table>

    <table style="margin-bottom: 12px; font-size: 10pt;">
        <tr>
            <td style="font-weight: bold; width: 120px;">Nama Pegawai</td>
            <td style="width: 15px; text-align: center;">:</td>
            <td style="font-weight: bold;" colspan="7">{{ $karyawan->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jabatan</td>
            <td style="text-align: center;">:</td>
            <td colspan="7">{{ $karyawan->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Seksi</td>
            <td style="text-align: center;">:</td>
            <td colspan="7">{{ $karyawan->nama_dept ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="9" style="height: 10px;"></td>
        </tr>
    </table>

    <table class="tabelpresensi" border="1">
        <thead>
            <tr>
                <th style="width: 45px;">No.</th>
                <th style="width: 95px;">Tanggal</th>
                <th style="width: 85px;">Jam Masuk</th>
                <th style="width: 90px;">Foto Masuk</th>
                <th style="width: 85px;">Jam Pulang</th>
                <th style="width: 90px;">Foto Pulang</th>
                <th style="width: 110px;">Status</th>
                <th style="width: 160px;">Keterangan</th>
                <th style="width: 80px;">Jml Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($presensi as $d)
                @if ($d->status == 'h')
                    @php
                        $file_in_candidates = [
                            public_path('storage/uploads/absensi/' . $d->foto_in),
                            storage_path('app/public/uploads/absensi/' . $d->foto_in),
                            public_path('uploads/absensi/' . $d->foto_in),
                        ];
                        $file_in = null;
                        foreach ($file_in_candidates as $c) {
                            if (!empty($d->foto_in) && file_exists($c)) {
                                $file_in = $c;
                                break;
                            }
                        }
                        $has_in = !empty($file_in);

                        $file_out_candidates = [
                            public_path('storage/uploads/absensi/' . $d->foto_out),
                            storage_path('app/public/uploads/absensi/' . $d->foto_out),
                            public_path('uploads/absensi/' . $d->foto_out),
                        ];
                        $file_out = null;
                        foreach ($file_out_candidates as $c) {
                            if (!empty($d->foto_out) && file_exists($c)) {
                                $file_out = $c;
                                break;
                            }
                        }
                        $has_out = !empty($file_out);

                        $terlambat = hitungjamterlambat($d->jam_masuk, $d->jam_in);
                        $terlambat_desimal = hitungjamterlambatdesimal($d->jam_masuk, $d->jam_in);
                        $j_terlambat = explode(':', $terlambat);
                        $jam_terlambat = intVal($j_terlambat[0]);
                        if ($jam_terlambat < 1) {
                            $jam_mulai = $d->jam_masuk;
                        } else {
                            $jam_mulai = $d->jam_in > $d->jam_masuk ? $d->jam_in : $d->jam_masuk;
                        }
                        $jam_berakhir = $d->jam_out > $d->jam_pulang ? $d->jam_pulang : $d->jam_out;
                        $total_jam = hitungjamkerja(
                            $d->tgl_presensi,
                            date('H:i', strtotime($jam_mulai)),
                            date('H:i', strtotime($jam_berakhir)),
                            $d->total_jam,
                            $d->lintashari,
                            date('H:i', strtotime($d->awal_jam_istirahat)),
                            date('H:i', strtotime($d->akhir_jam_istirahat)),
                        );
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center" style="mso-number-format: '\@';">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td class="text-center" style="mso-number-format: '\@';">{{ $d->jam_in }}</td>
                        <td class="text-center">
                            @if ($has_in)
                                <img src="{{ asset('storage/uploads/absensi/' . $d->foto_in) }}" width="40" height="48" alt="In">
                            @else
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                        <td class="text-center" style="mso-number-format: '\@';">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                        <td class="text-center">
                            @if ($has_out)
                                <img src="{{ asset('storage/uploads/absensi/' . $d->foto_out) }}" width="40" height="48" alt="Out">
                            @else
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                        <td class="text-center text-bold">
                            @if ($d->status == 'i')
                                Izin
                            @elseif ($d->status == 's')
                                Sakit
                            @elseif ($d->status == 'c')
                                Cuti
                            @elseif ($d->status == 'it')
                                Izin Terlambat
                            @elseif ($d->status == 'ipc')
                                Izin Pulang Cepat
                            @else
                                Hadir
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($d->jam_in > $d->jam_masuk)
                                <span style="color: #d9534f;">Terlambat {{ $terlambat_desimal }} Jam</span>
                            @else
                                <span style="color: #5cb85c;">Tepat Waktu</span>
                            @endif
                        </td>
                        <td class="text-center" style="mso-number-format: '\@';">
                            {{ $total_jam }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center" style="mso-number-format: '\@';">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center text-bold">
                            @if ($d->status == 'tl')
                                Tugas Luar
                            @elseif ($d->status == 'i')
                                Izin
                            @elseif ($d->status == 's')
                                Sakit
                            @elseif ($d->status == 'c')
                                Cuti
                            @elseif ($d->status == 'l')
                                Libur Nasional
                            @elseif ($d->status == 'it')
                                Izin Terlambat
                            @elseif ($d->status == 'ipc')
                                Izin Pulang Cepat
                            @else
                                {{ strtoupper($d->status) }}
                            @endif
                        </td>
                        <td class="text-center">{{ $d->keterangan ?? '-' }}</td>
                        <td class="text-center">-</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; font-style: italic; color: #777;">
                        Tidak ada data presensi pada bulan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 25px;">
        <tr>
            <td colspan="5"></td>
            <td colspan="4" style="text-align: center;">{{ $kota_cetak }}, {{ tgl_indo(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;">Mengetahui,</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat1_jabatan)) !!}</td>
            <td colspan="4" style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat2_jabatan)) !!}</td>
        </tr>
        <tr>
            <td colspan="5" style="height: 50px;"></td>
            <td colspan="4" style="height: 50px;"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat1_nama }}</u></b><br>
                <span style="mso-number-format: '\@';">NIP. {{ $pejabat1_nip }}</span>
            </td>
            <td colspan="4" style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat2_nama }}</u></b><br>
                <span style="mso-number-format: '\@';">NIP. {{ $pejabat2_nip }}</span>
            </td>
        </tr>
    </table>
</body>

</html>
