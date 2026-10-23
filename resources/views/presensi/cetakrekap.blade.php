<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi - {{ $namabulan[(int)$bulan] ?? '' }} {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #111;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
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

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
        }

        .tabelpresensi th {
            border: 0.5px solid #222;
            padding: 4px 1px;
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }

        .tabelpresensi td {
            border: 0.5px solid #222;
            padding: 3px 1px;
            text-align: center;
            vertical-align: middle;
        }

        .text-left {
            text-align: left !important;
            padding-left: 4px !important;
        }

        .legend-box {
            margin-top: 10px;
            font-size: 7.5pt;
        }

        .legend-item {
            display: inline-block;
            margin-right: 12px;
        }

        .color-badge {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 0.5px solid #555;
            vertical-align: middle;
            margin-right: 3px;
        }

        .libur-box {
            margin-top: 8px;
            font-size: 8pt;
        }

        .ttd-table {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
            font-size: 9pt;
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
                    <img src="{{ $logo_path }}" width="48" height="65" alt="Logo">
                @endif
            </td>
            <td class="header-title">
                <h2>REKAP PRESENSI BPKUK PROV. KALSEL</h2>
                <h3>PENYEDIA JASA LAINNYA PERORANGAN</h3>
                <p>BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} TAHUN {{ $tahun }}</p>
            </td>
            <td style="width: 65px;"></td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">NO.</th>
                <th rowspan="2" style="width: 140px;">NAMA PEGAWAI</th>
                <th colspan="{{ $jmlhari }}">BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} {{ $tahun }}</th>
                <th rowspan="2" style="width: 28px;">HADIR</th>
                <th colspan="2">IZIN / SAKIT</th>
                <th rowspan="2" style="width: 28px;">ALPA</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th style="width: 16px;">{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach
                <th style="width: 25px;">IZIN</th>
                <th style="width: 25px;">SAKIT</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left" style="font-weight: 500;">{{ $r->nama_lengkap }}</td>

                    @php
                        $jml_hadir = 0;
                        $jml_izin = 0;
                        $jml_sakit = 0;
                        $jml_cuti = 0;
                        $jml_alpa = 0;

                        for ($i = 1; $i <= $jmlhari; $i++) {
                            $tgl = 'tgl_' . $i;
                            $tgl_presensi = $rangetanggal[$i - 1];
                            $search_items = [
                                'nik' => $r->nik,
                                'tanggal_libur' => $tgl_presensi,
                            ];
                            $ceklibur = cekkaryawanlibur($datalibur, $search_items);

                            $datapresensi = explode('|', $r->$tgl ?? '');
                            if (!empty($r->$tgl)) {
                                $status = $datapresensi[2] ?? '';
                            } else {
                                $status = '';
                            }

                            $cekhari = gethari(date('D', strtotime($tgl_presensi)));
                            $color = '#ffffff';
                            $text_color = '#000000';
                            $text_display = '';

                            // Cek apakah hari ini merupakan hari kerja sesuai jadwal kantor/pegawai
                            $is_harikerja = cekJadwalKerja(
                                $r->nik, 
                                $r->kode_cabang ?? null, 
                                $r->kode_dept ?? null, 
                                $tgl_presensi, 
                                $cekhari, 
                                $jadwal_by_date ?? null, 
                                $jadwal_pegawai ?? null, 
                                $jadwal_dept ?? null
                            );

                            if ($cekhari == 'Sabtu' || $cekhari == 'Minggu') {
                                if ($status == 'h') {
                                    $jml_hadir += 1;
                                    $color = '#ffffff';
                                    $text_color = '#000000';
                                    $text_display = 'H';
                                } else {
                                    $color = '#fd7e14';
                                    $text_color = '#ffffff';
                                    $text_display = 'X';
                                }
                            } else {
                                if ($status == 'h') {
                                    $jml_hadir += 1;
                                    $color = '#ffffff';
                                    $text_color = '#000000';
                                    $text_display = 'H';
                                } elseif ($status == 'tl') {
                                    $jml_hadir += 1;
                                    $color = '#20c997';
                                    $text_color = '#ffffff';
                                    $text_display = 'TL';
                                } elseif ($status == 'i') {
                                    $jml_izin += 1;
                                    $color = '#ffc107';
                                    $text_color = '#000000';
                                    $text_display = 'I';
                                } elseif ($status == 's') {
                                    $jml_sakit += 1;
                                    $color = '#0dcaf0';
                                    $text_color = '#000000';
                                    $text_display = 'S';
                                } elseif ($status == 'c') {
                                    $jml_cuti += 1;
                                    $color = '#6f42c1';
                                    $text_color = '#ffffff';
                                    $text_display = 'C';
                                }

                                if (!empty($ceklibur)) {
                                    $color = '#198754';
                                    $text_color = '#ffffff';
                                    if (empty($text_display)) {
                                        $text_display = 'L';
                                    }
                                } elseif (!$is_harikerja) {
                                    // Hari libur kantor / tidak ada jadwal kerja
                                    $color = '#198754';
                                    $text_color = '#ffffff';
                                    if (empty($text_display)) {
                                        $text_display = 'L';
                                    }
                                } elseif (empty($status) && empty($ceklibur) && $is_harikerja) {
                                    $jml_alpa += 1;
                                    $color = '#dc3545';
                                    $text_color = '#ffffff';
                                    $text_display = 'A';
                                }
                            }
                    @endphp
                        <td style="background-color: {{ $color }}; color: {{ $text_color }}; font-weight: bold;">
                            {{ $text_display }}
                        </td>
                    @php
                        }
                    @endphp
                    <td style="font-weight: bold;">{{ !empty($jml_hadir) ? $jml_hadir : '-' }}</td>
                    <td style="font-weight: bold;">{{ !empty($jml_izin) ? $jml_izin : '-' }}</td>
                    <td style="font-weight: bold;">{{ !empty($jml_sakit) ? $jml_sakit : '-' }}</td>
                    <td style="font-weight: bold; color: {{ !empty($jml_alpa) ? '#d9534f' : '#000' }};">
                        {{ !empty($jml_alpa) ? $jml_alpa : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $jmlhari + 6 }}" style="padding: 15px; font-style: italic; color: #777;">
                        Tidak ada data pegawai yang dapat ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="legend-box" style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="vertical-align: top;">
                <b>Keterangan:</b> &nbsp;
                <span class="legend-item"><span class="color-badge" style="background-color: #ffffff;"></span><b>H</b> = Hadir</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #20c997;"></span><b>TL</b> = Tugas Luar</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #ffc107;"></span><b>I</b> = Izin</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #0dcaf0;"></span><b>S</b> = Sakit</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #6f42c1;"></span><b>C</b> = Cuti</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #198754;"></span><b>L</b> = Libur</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #fd7e14;"></span><b>X</b> = Sabtu & Minggu</span>
                <span class="legend-item"><span class="color-badge" style="background-color: #dc3545;"></span><b>A</b> = Alpa</span>
            </td>
        </tr>
    </table>

    @if ($harilibur->isNotEmpty())
        <div class="libur-box">
            <b>Daftar Hari Libur Nasional / Cuti Bersama:</b>
            <ol style="margin: 3px 0; padding-left: 18px;">
                @foreach ($harilibur as $d)
                    <li>{{ date('d-m-Y', strtotime($d->tanggal_libur)) }} : {{ $d->keterangan }}</li>
                @endforeach
            </ol>
        </div>
    @endif

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
                return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
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
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">{{ $kota_cetak }}, {{ tgl_indo(date('Y-m-d')) }}</td>
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
            <td style="height: 55px;"></td>
            <td style="height: 55px;"></td>
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
