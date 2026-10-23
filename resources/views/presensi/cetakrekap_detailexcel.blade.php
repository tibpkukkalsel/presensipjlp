<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Detail - {{ $namabulan[(int)$bulan] ?? '' }} {{ $tahun }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 4px 2px;
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
            vertical-align: top;
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

        $totalCols = $jmlhari + 7;
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
                <th rowspan="2" style="width: 180px;">NAMA PEGAWAI</th>
                <th colspan="{{ $jmlhari }}">BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} {{ $tahun }}</th>
                <th rowspan="2" style="width: 35px;">H</th>
                <th rowspan="2" style="width: 35px;">I</th>
                <th rowspan="2" style="width: 35px;">S</th>
                <th rowspan="2" style="width: 35px;">C</th>
                <th rowspan="2" style="width: 35px;">A</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th style="width: 55px;">{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $r)
                <tr>
                    <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                    <td class="text-left" style="font-weight: bold; vertical-align: middle;">{{ $r->nama_lengkap }}</td>

                    @php
                        $jml_hadir = 0;
                        $jml_izin = 0;
                        $jml_sakit = 0;
                        $jml_cuti = 0;
                        $jml_alpa = 0;
                        $color = '';

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
                                $jam_in = $datapresensi[0] ?? '';
                                $jam_out = $datapresensi[1] ?? '';
                                $status = $datapresensi[2] ?? '';
                                $nama_jam_kerja = $datapresensi[3] ?? '';
                                $jam_masuk = $datapresensi[4] ?? '';
                                $jam_pulang = $datapresensi[5] ?? '';
                                $total_jam = $datapresensi[8] ?? '';
                                $lintashari = $datapresensi[9] ?? '';
                                $awal_jam_istirahat = $datapresensi[10] ?? '';
                                $akhir_jam_istirahat = $datapresensi[11] ?? '';
                                $terlambat = hitungjamterlambat($jam_masuk, $jam_in);
                                $terlambat_desimal = hitungjamterlambatdesimal($jam_masuk, $jam_in);
                                $j_terlambat = explode(':', $terlambat);
                                $jam_terlambat = intVal($j_terlambat[0]);
                                if ($jam_terlambat < 1) {
                                    $jam_mulai = $jam_masuk;
                                } else {
                                    $jam_mulai = $jam_in > $jam_masuk ? $jam_in : $jam_masuk;
                                }
                                $jam_berakhir = $jam_out > $jam_pulang ? $jam_pulang : $jam_out;
                                $total_jam_kerja = hitungjamkerja(
                                    $tgl_presensi,
                                    date('H:i', strtotime($jam_mulai)),
                                    date('H:i', strtotime($jam_berakhir)),
                                    $total_jam,
                                    $lintashari,
                                    date('H:i', strtotime($awal_jam_istirahat)),
                                    date('H:i', strtotime($akhir_jam_istirahat)),
                                );
                            } else {
                                $status = '';
                                $jam_in = '';
                                $jam_out = '';
                                $jam_masuk = '';
                                $jam_pulang = '';
                                $nama_jam_kerja = '';
                                $total_jam_kerja = 0;
                                $terlambat = 0;
                                $terlambat_desimal = 0;
                            }

                            $cekhari = gethari(date('D', strtotime($tgl_presensi)));
                            $color = '#ffffff';

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
                                } else {
                                    $color = '#fd7e14';
                                }
                            } else {
                                if ($status == 'h') {
                                    $jml_hadir += 1;
                                    $color = '#ffffff';
                                } elseif ($status == 'tl') {
                                    $jml_hadir += 1;
                                    $color = '#20c997';
                                } elseif ($status == 'i') {
                                    $jml_izin += 1;
                                    $color = '#ffbb00';
                                } elseif ($status == 's') {
                                    $jml_sakit += 1;
                                    $color = '#34a1eb';
                                } elseif ($status == 'c') {
                                    $jml_cuti += 1;
                                    $color = '#a600ff';
                                }

                                if (!empty($ceklibur)) {
                                    $color = '#198754';
                                } elseif (!$is_harikerja) {
                                    // Hari libur kantor / tidak ada jadwal kerja
                                    $color = '#198754';
                                } elseif (empty($status) && empty($ceklibur) && $is_harikerja) {
                                    $jml_alpa += 1;
                                    $color = '#dc3545';
                                }
                            }
                    @endphp
                        <td style="background-color: {{ $color }}; vertical-align: middle;">
                            @if ($status == 'h')
                                <b>{{ $nama_jam_kerja }}</b><br>
                                <span style="color: green;">{{ $jam_masuk }}-{{ $jam_pulang }}</span><br>
                                <span style="color: #b76e00;">{{ $jam_in }}-{{ $jam_out }}</span><br>
                                <span style="color: blue;">Jam: {{ $total_jam_kerja }}</span>
                            @elseif ($cekhari == 'Sabtu' || $cekhari == 'Minggu')
                                <span style="color: white; font-weight: bold;">X</span>
                            @elseif ($status == 'tl')
                                <span style="color: white; font-weight: bold;">TL</span>
                            @elseif ($status == 'i')
                                <span style="font-weight: bold;">I</span>
                            @elseif ($status == 's')
                                <span style="font-weight: bold;">S</span>
                            @elseif ($status == 'c')
                                <span style="color: white; font-weight: bold;">C</span>
                            @elseif (!empty($ceklibur))
                                <span style="color: white; font-weight: bold;">L</span>
                            @elseif (!$is_harikerja)
                                <span style="color: white; font-weight: bold;">L</span>
                            @elseif (empty($status) && empty($ceklibur) && $is_harikerja)
                                <span style="color: white; font-weight: bold;">A</span>
                            @endif
                        </td>
                    @php
                        }
                    @endphp
                    <td class="text-bold" style="vertical-align: middle;">{{ !empty($jml_hadir) ? $jml_hadir : '-' }}</td>
                    <td class="text-bold" style="vertical-align: middle;">{{ !empty($jml_izin) ? $jml_izin : '-' }}</td>
                    <td class="text-bold" style="vertical-align: middle;">{{ !empty($jml_sakit) ? $jml_sakit : '-' }}</td>
                    <td class="text-bold" style="vertical-align: middle;">{{ !empty($jml_cuti) ? $jml_cuti : '-' }}</td>
                    <td class="text-bold" style="vertical-align: middle; color: {{ !empty($jml_alpa) ? '#d9534f' : '#000' }};">
                        {{ !empty($jml_alpa) ? $jml_alpa : '-' }}
                    </td>
                </tr>
            @endforeach
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
