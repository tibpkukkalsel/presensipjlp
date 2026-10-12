<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
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

<body class="A4">

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
                          LAPORAN PENERIMAAN SAMSAT KELILING<br>
                          UPPD PELAIHARI<br>
                    </span>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td>Nama Mobil</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }} ({{ $karyawan->nama_dept }})</td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>:</td>
                <td>{{ ($namabulan[$bulan])}}</td>
            </tr>
            <tr>
                <td>Tahun</td>
                <td>:</td>
                <td>{{ $tahun }}</td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Roda 2 (Rp)</th>
                <th>Roda 4 (Rp)</th>
                <th>Jumlah (Rp)</th>
                <th>Notice Keluar</th>
            </tr>
            </thead>

            @foreach ($penerimaan as $d)

                <tbody>
                    <tr>
                        <td align='center'>{{ $loop->iteration }}</td>
                        <td align='center'>{{ date('d-m-Y', strtotime($d->tgl_penerimaan)) }}</td>
                        <td>{{ $d->nama_cabang }}</td>
                        <td><?php echo number_format($d->r2),0; ?></td>
                        <td><?php echo number_format($d->r4),0; ?></td>
                        <td><?php echo number_format($d->jumlah),0; ?></td>
                        <td align='center'>{{ $d->notice_keluar }}</td>
                    </tr>
                </tbody>
                @endforeach
                <tfoot>
                    <tr>
                        <th colspan="3">Jumlah</th>
                        <th><?php echo number_format($totalr2),0; ?></th>
                        <th><?php echo number_format($totalr4),0; ?></th>
                        <th><?php echo number_format($totalpenerimaan),0; ?></th>
                        <th><?php echo number_format($totalnotice); ?></th>
                    </tr>
                    </tfoot>

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
                    <b><u>FEBRI</b></u><br>
                    <span>NIP. 19800813 200712 1 001<span>
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
