<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Laporan Realisasi Penerimaan</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: legal;
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
            padding: 5px;
            background-color: #dbdbdb;
            font-size: 12px;
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

<body class="legal landscape">

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
                          LAPORAN REALISASI PENERIMAAN SAMSAT KELILING<br>
                          UPPD PELAIHARI<br>
                          TAHUN {{ $tahun }}
                    </span>
                </td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <thead>
            <tr>
            <th rowspan="2" width="1">No.</th>
            <th rowspan="2" width="130">Mobil</th>
            <th colspan="12">Bulan</th>
            <th rowspan="2" width="90">Jumlah</th>
            </tr>
            <tr>
                <th width="90">Januari</th>
                <th width="90">Februari</th>
                <th width="90">Maret</th>
                <th width="90">April</th>
                <th width="90">Mei</th>
                <th width="90">Juni</th>
                <th width="90">Juli</th>
                <th width="90">Agustus</th>
                <th width="90">September</th>
                <th width="90">Oktober</th>
                <th width="90">November</th>
                <th width="90">Desember</th>
            </tr>
            </thead>

            @foreach ($penerimaan as $d)
                <tbody>
                    <tr>
                        <td align='center'>{{ $loop->iteration }}</td>
                        <td align='center'>{{ $d->nama_lengkap }}</td>
                        <td align='center'><?php echo number_format($d->j1),0;?></td>
                        <td align='center'><?php echo number_format($d->j2),0;?></td>
                        <td align='center'><?php echo number_format($d->j3),0;?></td>
                        <td align='center'><?php echo number_format($d->j4),0;?></td>
                        <td align='center'><?php echo number_format($d->j5),0;?></td>
                        <td align='center'><?php echo number_format($d->j6),0;?></td>
                        <td align='center'><?php echo number_format($d->j7),0;?></td>
                        <td align='center'><?php echo number_format($d->j8),0;?></td>
                        <td align='center'><?php echo number_format($d->j9),0;?></td>
                        <td align='center'><?php echo number_format($d->j10),0;?></td>
                        <td align='center'><?php echo number_format($d->j11),0;?></td>
                        <td align='center'><?php echo number_format($d->j12),0;?></td>
                        <td align='center'><?php echo number_format($d->total),0;?></td>
                    </tr>
                </tbody>
                @endforeach
                <tfoot>
                    <tr>
                        <th colspan="2">Jumlah</th>
                        <th><?php echo number_format($total1),0;?></th>
                        <th><?php echo number_format($total2),0;?></th>
                        <th><?php echo number_format($total3),0;?></th>
                        <th><?php echo number_format($total4),0;?></th>
                        <th><?php echo number_format($total5),0;?></th>
                        <th><?php echo number_format($total6),0;?></th>
                        <th><?php echo number_format($total7),0;?></th>
                        <th><?php echo number_format($total8),0;?></th>
                        <th><?php echo number_format($total9),0;?></th>
                        <th><?php echo number_format($total10),0;?></th>
                        <th><?php echo number_format($total11),0;?></th>
                        <th><?php echo number_format($total12),0;?></th>
                        <th><?php echo number_format($totaltahun),0;?></th>
                    </tr>
                    </tfoot>

        </table>

        <table width="100%" style="margin-top:100px">
            <tr>
                <td style="text-align: center"></td>

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
