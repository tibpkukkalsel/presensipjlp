<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Keterangan Laporan Presensi - {{ $namabulan[(int)$bulan] ?? '' }} {{ $tahun }}</title>
    <style>
        .header-title {
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .tabelpresensi {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 9pt;
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 6px;
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .text-left {
            text-align: left !important;
        }
    </style>
</head>

<body>
    <table style="width: 100%;">
        <tr>
            <td colspan="14" style="text-align: center; font-size: 14pt; font-weight: bold;">
                REKAP KETERANGAN LAPORAN PRESENSI
            </td>
        </tr>
        <tr>
            <td colspan="14" style="text-align: center; font-size: 11pt; font-weight: bold;">
                BALAI PELATIHAN KOPERASI DAN USAHA KECIL PROVINSI KALIMANTAN SELATAN
            </td>
        </tr>
        <tr>
            <td colspan="14" style="text-align: center; font-size: 10pt; font-weight: bold;">
                BULAN {{ strtoupper($namabulan[(int)$bulan] ?? '') }} TAHUN {{ $tahun }}
            </td>
        </tr>
        <tr>
            <td colspan="14"></td>
        </tr>
    </table>

    <table class="tabelpresensi">
        <thead>
            <tr>
                <th rowspan="2">NO.</th>
                <th rowspan="2">NAMA PEGAWAI</th>
                <th colspan="11">JUMLAH</th>
                <th rowspan="2">PERSENTASE KEHADIRAN</th>
            </tr>
            <tr>
                <th>HARI KERJA</th>
                <th>HADIR</th>
                <th>IZIN TERLAMBAT</th>
                <th>TERLAMBAT</th>
                <th>IZIN PULANG CEPAT</th>
                <th>PULANG CEPAT</th>
                <th>TUGAS LUAR</th>
                <th>CUTI</th>
                <th>SAKIT</th>
                <th>IZIN TIDAK MASUK KERJA</th>
                <th>TANPA KETERANGAN</th>
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
                    <td class="text-left" style="mso-number-format: '\@';">
                        {{ $r->nama_lengkap }}
                    </td>
                    <td>{{ $r->h_kerja }}</td>
                    <td>{{ $r->hadir }}</td>
                    <td>{{ $r->izin_terlambat }}</td>
                    <td>{{ $r->terlambat }}</td>
                    <td>{{ $r->izin_pulang_cepat }}</td>
                    <td>{{ $r->pulang_cepat }}</td>
                    <td>{{ $r->tugas_luar }}</td>
                    <td>{{ $r->cuti }}</td>
                    <td>{{ $r->sakit }}</td>
                    <td>{{ $r->izin }}</td>
                    <td>{{ $r->tanpa_keterangan }}</td>
                    <td style="font-weight: bold;">{{ $r->persentase }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="text-align: center; font-style: italic;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        @if ($count_pegawai > 0)
            <tfoot>
                <tr style="background-color: #d9d9d9; font-weight: bold;">
                    <td colspan="2" style="text-align: center;">TOTAL / RATA-RATA</td>
                    <td>{{ round($tot_h_kerja / $count_pegawai) }}</td>
                    <td>{{ $tot_hadir }}</td>
                    <td>{{ $tot_it }}</td>
                    <td>{{ $tot_terlambat }}</td>
                    <td>{{ $tot_ipc }}</td>
                    <td>{{ $tot_pc }}</td>
                    <td>{{ $tot_tl }}</td>
                    <td>{{ $tot_cuti }}</td>
                    <td>{{ $tot_sakit }}</td>
                    <td>{{ $tot_izin }}</td>
                    <td>{{ $tot_tk }}</td>
                    <td>{{ round($tot_persen / $count_pegawai, 1) }}%</td>
                </tr>
            </tfoot>
        @endif
    </table>

    @php
        $kota_cetak = $konfigurasi_laporan->kota_cetak ?? 'Pelaihari';
        $pejabat1_jabatan = $konfigurasi_laporan->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL';
        $pejabat1_nama = $konfigurasi_laporan->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM';
        $pejabat1_nip = $konfigurasi_laporan->pejabat1_nip ?? '19870222 200701 1 002';
        $pejabat2_jabatan = $konfigurasi_laporan->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA';
        $pejabat2_nama = $konfigurasi_laporan->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST';
        $pejabat2_nip = $konfigurasi_laporan->pejabat2_nip ?? '19800813 200712 1 001';
    @endphp

    <table style="width: 100%; margin-top: 30px; font-family: Arial, sans-serif;">
        <tr>
            <td colspan="8"></td>
            <td colspan="6" style="text-align: center;">{{ $kota_cetak }}, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">Mengetahui,</td>
            <td colspan="7" style="text-align: center;"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center; font-weight: bold;">{{ $pejabat1_jabatan }}</td>
            <td colspan="7" style="text-align: center; font-weight: bold;">{{ $pejabat2_jabatan }}</td>
        </tr>
        <tr>
            <td colspan="14" style="height: 40px;"></td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ $pejabat1_nama }}</td>
            <td colspan="7" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ $pejabat2_nama }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center; mso-number-format: '\@';">NIP. {{ $pejabat1_nip }}</td>
            <td colspan="7" style="text-align: center; mso-number-format: '\@';">NIP. {{ $pejabat2_nip }}</td>
        </tr>
    </table>
</body>

</html>

