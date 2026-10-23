<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Presensi - {{ $karyawan->nama_lengkap ?? '' }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.3;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .header-title {
            text-align: center;
            vertical-align: middle;
        }

        .header-title h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-title h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 2px 0;
            padding: 0;
            text-transform: uppercase;
        }

        .header-title p {
            font-size: 10pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .tabeldatakaryawan {
            margin-bottom: 12px;
            font-size: 9.5pt;
        }

        .tabeldatakaryawan td {
            padding: 2px 4px;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .tabelpresensi th {
            border: 0.5px solid #222;
            padding: 5px 3px;
            background-color: #eaeaea;
            font-weight: bold;
            text-align: center;
        }

        .tabelpresensi td {
            border: 0.5px solid #222;
            padding: 4px 3px;
            vertical-align: middle;
        }

        .foto-presensi {
            width: 40px;
            height: 48px;
            object-fit: cover;
            border-radius: 3px;
            border: 0.5px solid #ccc;
        }

        .text-center {
            text-align: center;
        }

        .badge-status {
            font-weight: bold;
        }

        .ttd-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
            font-size: 9.5pt;
        }

        .ttd-table td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    @php
        $logo_path = str_replace('\\', '/', public_path('assets/img/Logo Provinsi Kalimantan Selatan.png'));
        $has_logo = file_exists($logo_path);
    @endphp

    <table class="header-table">
        <tr>
            <td style="width: 65px; text-align: center; vertical-align: middle;">
                @if ($has_logo)
                    <img src="{{ $logo_path }}" width="50" height="68" alt="Logo">
                @endif
            </td>
            <td class="header-title">
                <h2>LAPORAN PRESENSI BPKUK PROV. KALSEL</h2>
                <h3>PENYEDIA JASA LAINNYA PERORANGAN</h3>
                <p>BULAN {{ strtoupper($namabulan[(int) $bulan] ?? '') }} TAHUN {{ $tahun }}</p>
            </td>
            <td style="width: 65px;"></td>
        </tr>
    </table>

    <table class="tabeldatakaryawan">
        <tr>
            <td style="width: 110px; font-weight: bold;">Nama Pegawai</td>
            <td style="width: 10px;">:</td>
            <td style="font-weight: bold;">{{ $karyawan->nama_lengkap ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Seksi</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept ?? '-' }}</td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <thead>
            <tr>
                <th style="width: 4%;">No.</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 9%;">Jam Masuk</th>
                <th style="width: 11%;">Foto</th>
                <th style="width: 9%;">Jam Pulang</th>
                <th style="width: 11%;">Foto</th>
                <th style="width: 14%;">Status</th>
                <th style="width: 18%;">Keterangan</th>
                <th style="width: 12%;">Jml Jam</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($presensi as $d)
                @if ($d->status == 'h')
                    @php
                        $file_in = public_path('storage/uploads/absensi/' . $d->foto_in);
                        $has_in = !empty($d->foto_in) && file_exists($file_in);
                        $path_in = $has_in ? str_replace('\\', '/', $file_in) : null;

                        $file_out = public_path('storage/uploads/absensi/' . $d->foto_out);
                        $has_out = !empty($d->foto_out) && file_exists($file_out);
                        $path_out = $has_out ? str_replace('\\', '/', $file_out) : null;

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
                        <td class="text-center">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td class="text-center">{{ $d->jam_in }}</td>
                        <td class="text-center">
                            @if ($has_in)
                                <img src="{{ $path_in }}" class="foto-presensi" alt="In">
                            @else
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                        <td class="text-center">
                            @if ($has_out)
                                <img src="{{ $path_out }}" class="foto-presensi" alt="Out">
                            @else
                                <span style="color: #888;">-</span>
                            @endif
                        </td>
                        <td class="text-center badge-status">
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
                        <td class="text-center">
                            {{ $total_jam }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-center badge-status">
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
    @endphp

    @php
        $kota_cetak = $konfigurasi_laporan->kota_cetak ?? 'Pelaihari';
        $pejabat1_jabatan = $konfigurasi_laporan->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL';
        $pejabat1_nama = $konfigurasi_laporan->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM';
        $pejabat1_nip = $konfigurasi_laporan->pejabat1_nip ?? '19870222 200701 1 002';
        $pejabat2_jabatan = $konfigurasi_laporan->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA';
        $pejabat2_nama = $konfigurasi_laporan->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST';
        $pejabat2_nip = $konfigurasi_laporan->pejabat2_nip ?? '19800813 200712 1 001';
    @endphp

    <table class="ttd-table">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: center;">{{ $kota_cetak }}, {{ tgl_indo(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td style="text-align: center;">Mengetahui,</td>
            <td style="text-align: center;"></td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat1_jabatan)) !!}</td>
            <td style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat2_jabatan)) !!}</td>
        </tr>
        <tr>
            <td style="height: 60px;"></td>
            <td style="height: 60px;"></td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat1_nama }}</u></b><br>
                <span>NIP. {{ $pejabat1_nip }}</span>
            </td>
            <td style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat2_nama }}</u></b><br>
                <span>NIP. {{ $pejabat2_nip }}</span>
            </td>
        </tr>
    </table>
</body>

</html>
