<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Keterangan Laporan Presensi - {{ $namabulan[(int)$bulan] ?? '' }} {{ $tahun }}</title>
    <style>
        @page {
            sheet-size: A4-L;
            margin: 8mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            color: #111;
            line-height: 1.2;
            background: #ffffff;
            margin: 0;
            padding: 0;
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
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-title h3 {
            font-size: 10.5pt;
            font-weight: bold;
            margin: 2px 0;
            padding: 0;
            text-transform: uppercase;
        }

        .header-title p {
            font-size: 9pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .tabelpresensi {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            table-layout: fixed;
        }

        .tabelpresensi th {
            border: 0.5px solid #222;
            padding: 4px 2px;
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            line-height: 1.15;
        }

        .tabelpresensi td {
            border: 0.5px solid #222;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
            line-height: 1.15;
            word-wrap: break-word;
        }

        .text-left {
            text-align: left !important;
            padding-left: 5px !important;
        }

        .ttd-table {
            width: 100%;
            margin-top: 18px;
            page-break-inside: avoid;
            font-size: 8.5pt;
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
                    <img src="{{ $logo_path }}" width="45" height="60" alt="Logo">
                @endif
            </td>
            <td class="header-title">
                <h2>REKAP KETERANGAN LAPORAN PRESENSI</h2>
                <h3>BALAI PELATIHAN KOPERASI DAN USAHA KECIL PROVINSI KALIMANTAN SELATAN</h3>
                <p>BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} TAHUN {{ $tahun }}</p>
            </td>
            <td style="width: 65px;"></td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">NO.</th>
                <th rowspan="2" style="width: 18%;">NAMA PEGAWAI</th>
                <th colspan="11">JUMLAH</th>
                <th rowspan="2" style="width: 8%;">PERSENTASE<br>KEHADIRAN</th>
            </tr>
            <tr>
                <th style="width: 6.5%;">HARI<br>KERJA</th>
                <th style="width: 5.5%;">HADIR</th>
                <th style="width: 6.5%;">IZIN<br>TERLAMBAT</th>
                <th style="width: 6.5%;">TERLAMBAT</th>
                <th style="width: 6.5%;">IZIN PULANG<br>CEPAT</th>
                <th style="width: 6.5%;">PULANG<br>CEPAT</th>
                <th style="width: 6%;">TUGAS<br>LUAR</th>
                <th style="width: 5%;">CUTI</th>
                <th style="width: 5%;">SAKIT</th>
                <th style="width: 8.5%;">IZIN TIDAK<br>MASUK KERJA</th>
                <th style="width: 8%;">TANPA<br>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tot_h_kerja = 0;
                $tot_hadir = 0;
                $tot_it = 0;
                $tot_terlambat = 0;
                $tot_ipc = 0;
                $tot_pc = 0;
                $tot_tl = 0;
                $tot_cuti = 0;
                $tot_sakit = 0;
                $tot_izin = 0;
                $tot_tk = 0;
                $tot_persen = 0;
                $count_pegawai = count($laporan);
            @endphp
            @forelse ($laporan as $r)
                @php
                    $tot_h_kerja += $r->h_kerja;
                    $tot_hadir += $r->hadir;
                    $tot_it += $r->izin_terlambat;
                    $tot_terlambat += $r->terlambat;
                    $tot_ipc += $r->izin_pulang_cepat;
                    $tot_pc += $r->pulang_cepat;
                    $tot_tl += $r->tugas_luar;
                    $tot_cuti += $r->cuti;
                    $tot_sakit += $r->sakit;
                    $tot_izin += $r->izin;
                    $tot_tk += $r->tanpa_keterangan;
                    $tot_persen += $r->persentase;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-left">
                        <b>{{ $r->nama_lengkap }}</b><br>
                        <span style="font-size: 6.5pt; color: #555;">{{ $r->jabatan ?? '-' }}</span>
                    </td>
                    <td style="font-weight: bold;">{{ $r->h_kerja }}</td>
                    <td>{{ $r->hadir > 0 ? $r->hadir : '-' }}</td>
                    <td>{{ $r->izin_terlambat > 0 ? $r->izin_terlambat : '-' }}</td>
                    <td style="{{ $r->terlambat > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                        {{ $r->terlambat > 0 ? $r->terlambat : '-' }}
                    </td>
                    <td>{{ $r->izin_pulang_cepat > 0 ? $r->izin_pulang_cepat : '-' }}</td>
                    <td style="{{ $r->pulang_cepat > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                        {{ $r->pulang_cepat > 0 ? $r->pulang_cepat : '-' }}
                    </td>
                    <td>{{ $r->tugas_luar > 0 ? $r->tugas_luar : '-' }}</td>
                    <td>{{ $r->cuti > 0 ? $r->cuti : '-' }}</td>
                    <td>{{ $r->sakit > 0 ? $r->sakit : '-' }}</td>
                    <td>{{ $r->izin > 0 ? $r->izin : '-' }}</td>
                    <td style="{{ $r->tanpa_keterangan > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                        {{ $r->tanpa_keterangan > 0 ? $r->tanpa_keterangan : '-' }}
                    </td>
                    <td style="font-weight: bold; background-color: #f8fafc;">
                        {{ $r->persentase }}%
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="padding: 15px; font-style: italic; color: #777;">
                        Tidak ada data pegawai yang dapat ditampilkan untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($count_pegawai > 0)
            <tfoot>
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="2" style="text-align: center;">TOTAL / RATA-RATA</td>
                    <td>{{ $count_pegawai > 0 ? round($tot_h_kerja / $count_pegawai) : 0 }}</td>
                    <td>{{ $tot_hadir }}</td>
                    <td>{{ $tot_it }}</td>
                    <td>{{ $tot_terlambat }}</td>
                    <td>{{ $tot_ipc }}</td>
                    <td>{{ $tot_pc }}</td>
                    <td>{{ $tot_tl }}</td>
                    <td>{{ $tot_cuti }}</td>
                    <td>{{ $tot_sakit }}</td>
                    <td>{{ $tot_izin }}</td>
                    <td style="{{ $tot_tk > 0 ? 'color: #dc2626;' : '' }}">{{ $tot_tk }}</td>
                    <td style="background-color: #e2e8f0;">
                        {{ $count_pegawai > 0 ? round($tot_persen / $count_pegawai, 1) : 0 }}%
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    @php
        if (!function_exists('tgl_indo_ket')) {
            function tgl_indo_ket($tanggal) {
                $bulan = [
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
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
    @endphp

    <table class="ttd-table">
        <tr>
            <td style="width: 55%;"></td>
            <td style="width: 45%; text-align: center;">{{ $kota_cetak }}, {{ tgl_indo_ket(date('Y-m-d')) }}</td>
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
            <td style="height: 45px;"></td>
            <td style="height: 45px;"></td>
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
