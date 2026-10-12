<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LAPORAN PRESENSI</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: Legal
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabeldatakaryawan tr td {
            padding: 5px;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelpresensi tr th {
            border: 1px solid #131212;
            padding: 8px;
            background-color: #dbdbdb;
        }

        .tabelpresensi tr td {
            border: 1px solid #131212;
            padding: 5px;
            font-size: 12px;
        }

        .foto {
            width: 40px;
            height: 30px;

        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="legal">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

    <table style="width: 100%">
            <tr>
                <td style="width: 60px">
                    <img src="{{ asset('assets/img/Logo Provinsi Kalimantan Selatan.png') }}" width="50" height="70" alt="">
                </td>
                <td>
                    <span id="title">
                          LAPORAN PRESENSI SAMSAT KELILING<br>
                          UPPD PELAIHARI<br>
                          BULAN {{ strtoupper($namabulan[$bulan])}}
                    </span>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td>Mobil <br></td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</span></td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <th width="1">No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Jml Jam</th>
            </tr>

            @foreach ($presensi as $d)
                @if ($d->status == 'h')
                    @php
                        $path_in = Storage::url('uploads/absensi/' . $d->foto_in);
                        $path_out = Storage::url('uploads/absensi/' . $d->foto_out);
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
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="center">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td align="center" width="50">{{ $d->jam_in }}</td>
                        <td align="center"><img src="{{ url($path_in) }}" alt="" height="90"></td>
                        <td align="center" width="50">{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                        <td align="center">
                            @if ($d->jam_out != null)
                                <img src="{{ url($path_out) }}" alt="" height="90">
                            @else
                                <img src="{{ asset('assets/img/camera.jpg') }}" alt="" height="90">
                            @endif
                        </td>
                        <td style="text-align: center">
                        @if ($d->status == 'i')
                                            Service Mobil
                                        @elseif ($d->status == 's')
                                            Service Perangkat
                                        @elseif ($d->status = 'h')
                                            Hadir
                                        @endif
                        </td>
                        <td align="center">
                            @if ($d->jam_in > $d->jam_masuk)
                                Terlambat {{ $terlambat_desimal }} Jam
                            @else
                                Tepat Waktu
                            @endif
                        </td>
                        <td align="center">
                            {{ $total_jam }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align='center'>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td align="center"></td>
                        <td style="text-align: center"> @if ($d->status == 'i')
                                            Service Mobil
                                        @elseif ($d->status == 's')
                                            Service Perangkat
                                        @elseif ($d->status = 'h')
                                            Hadir
                                        @endif</td>
                        <td align="center">{{ $d->keterangan }}</td>
                        <td align="center"></td>
                    </tr>
                @endif
            @endforeach
        </table>

        <table width="100%" style="margin-top:100px">
            <tr>
                <td style="text-align: center"></td>
                <?php
function tgl_indo($tanggal){
	$bulan = array (
		1 =>   'Januari',
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
		'Desember'
	);
	$pecahkan = explode('-', $tanggal);

	// variabel pecahkan 0 = tanggal
	// variabel pecahkan 1 = bulan
	// variabel pecahkan 2 = tahun

	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    } ?>
                <td style="text-align: center">Pelaihari, <?php echo tgl_indo(date('Y-m-d')); ?></td>
            </tr>
            <tr>
                <td style="text-align: center">Mengetahui,</td>

                <td style="text-align: center"></td>
            </tr>
            <tr>
                <td style="text-align: center"><b>KEPALA UPPD PELAIHARI</b></td>

                <td style="text-align: center"><b>KEPALA SEKSI PELAYANAN <br> PKB & BBNKB</b></td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align:bottom" height="100px">
                    <b><u>FEBRY RAHARJO, S.IP, MM</b></u><br>
                    <span>NIP. 19870222 200701 1 002 <span>
                </td>
                <td style="text-align: center; vertical-align:bottom">
                <b><u>ADIMAS FITRIANDI, ST</b></u><br>
                <span>NIP. 19800813 200712 1 001<span>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
