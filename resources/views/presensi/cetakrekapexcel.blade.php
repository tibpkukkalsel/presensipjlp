<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi - {{ $namabulan[(int)$bulan] ?? '' }} {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 5px 2px;
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
            vertical-align: middle;
        }

        .text-left {
            text-align: left !important;
        }

        .text-center {
            text-align: center !important;
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
                return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
            }
        }

        $kota_cetak = $konfigurasi_laporan->kota_cetak ?? 'Pelaihari';
        $pejabat1_jabatan = $konfigurasi_laporan->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL';
        $pejabat1_nama = $konfigurasi_laporan->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM';
        $pejabat1_nip = $konfigurasi_laporan->pejabat1_nip ?? '19870222 200701 1 002';
        $pejabat2_jabatan = $konfigurasi_laporan->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA';
        $pejabat2_nama = $konfigurasi_laporan->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST';
        $pejabat2_nip = $konfigurasi_laporan->pejabat2_nip ?? '19800813 200712 1 001';

        $totalCols = $jmlhari + 6;
    @endphp

    <table style="width: 100%;">
        <tr>
            <th colspan="{{ $totalCols }}" style="font-size: 14pt; font-weight: bold; text-align: center;">REKAP PRESENSI BPKUK PROV. KALSEL</th>
        </tr>
        <tr>
            <th colspan="{{ $totalCols }}" style="font-size: 12pt; font-weight: bold; text-align: center;">PENYEDIA JASA LAINNYA PERORANGAN</th>
        </tr>
        <tr>
            <th colspan="{{ $totalCols }}" style="font-size: 11pt; font-weight: bold; text-align: center;">BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} TAHUN {{ $tahun }}</th>
        </tr>
        <tr>
            <td colspan="{{ $totalCols }}" style="height: 15px;"></td>
        </tr>
    </table>

    <table class="tabelpresensi" border="1">
        <thead>
            <tr>
                <th rowspan="2" style="width: 35px;">NO.</th>
                <th rowspan="2" style="width: 200px;">NAMA PEGAWAI</th>
                <th colspan="{{ $jmlhari }}">BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} {{ $tahun }}</th>
                <th rowspan="2" style="width: 45px;">HADIR</th>
                <th colspan="2">IZIN / SAKIT</th>
                <th rowspan="2" style="width: 45px;">ALPA</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th style="width: 28px;">{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach
                <th style="width: 40px;">IZIN</th>
                <th style="width: 40px;">SAKIT</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left" style="font-weight: bold;">{{ $r->nama_lengkap }}</td>

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
                    <td class="text-bold">{{ !empty($jml_hadir) ? $jml_hadir : '-' }}</td>
                    <td class="text-bold">{{ !empty($jml_izin) ? $jml_izin : '-' }}</td>
                    <td class="text-bold">{{ !empty($jml_sakit) ? $jml_sakit : '-' }}</td>
                    <td class="text-bold" style="color: {{ !empty($jml_alpa) ? '#d9534f' : '#000' }};">
                        {{ !empty($jml_alpa) ? $jml_alpa : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $totalCols }}" style="padding: 15px; font-style: italic; color: #777;">
                        Tidak ada data pegawai yang dapat ditampilkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 15px; font-size: 9pt;">
        <tr>
            <td colspan="{{ $totalCols }}">
                <b>Keterangan:</b> &nbsp;
                <b>H</b> = Hadir &nbsp;|&nbsp;
                <b>TL</b> = Tugas Luar &nbsp;|&nbsp;
                <b>I</b> = Izin &nbsp;|&nbsp;
                <b>S</b> = Sakit &nbsp;|&nbsp;
                <b>C</b> = Cuti &nbsp;|&nbsp;
                <b>L</b> = Libur &nbsp;|&nbsp;
                <b>X</b> = Sabtu & Minggu &nbsp;|&nbsp;
                <b>A</b> = Alpa
            </td>
        </tr>
        @if ($harilibur->isNotEmpty())
            <tr>
                <td colspan="{{ $totalCols }}" style="padding-top: 8px;">
                    <b>Daftar Hari Libur Nasional / Cuti Bersama:</b>
                </td>
            </tr>
            @foreach ($harilibur as $d)
                <tr>
                    <td colspan="{{ $totalCols }}" style="padding-left: 15px;">
                        - {{ date('d-m-Y', strtotime($d->tanggal_libur)) }} : {{ $d->keterangan }}
                    </td>
                </tr>
            @endforeach
        @endif
    </table>

    @php
        $halfCols = floor($totalCols / 2);
        $remCols = $totalCols - $halfCols;
    @endphp

    <table style="width: 100%; margin-top: 25px; font-size: 10pt;">
        <tr>
            <td colspan="{{ $halfCols }}"></td>
            <td colspan="{{ $remCols }}" style="text-align: center;">{{ $kota_cetak }}, {{ tgl_indo(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td colspan="{{ $halfCols }}" style="text-align: center;">Mengetahui,</td>
            <td colspan="{{ $remCols }}"></td>
        </tr>
        <tr>
            <td colspan="{{ $halfCols }}" style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat1_jabatan)) !!}</td>
            <td colspan="{{ $remCols }}" style="text-align: center; font-weight: bold;">{!! nl2br(e($pejabat2_jabatan)) !!}</td>
        </tr>
        <tr>
            <td colspan="{{ $halfCols }}" style="height: 50px;"></td>
            <td colspan="{{ $remCols }}" style="height: 50px;"></td>
        </tr>
        <tr>
            <td colspan="{{ $halfCols }}" style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat1_nama }}</u></b><br>
                <span style="mso-number-format: '\@';">NIP. {{ $pejabat1_nip }}</span>
            </td>
            <td colspan="{{ $remCols }}" style="text-align: center; vertical-align: bottom;">
                <b><u>{{ $pejabat2_nama }}</u></b><br>
                <span style="mso-number-format: '\@';">NIP. {{ $pejabat2_nip }}</span>
            </td>
        </tr>
    </table>
</body>

</html>
