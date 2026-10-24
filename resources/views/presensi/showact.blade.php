@php
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $hariIndo = [
        'Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu',
        'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'
    ];

    $namaPegawai = $dataizin->nama_lengkap ?? Auth::guard('karyawan')->user()->nama_lengkap ?? '';

    // Format Tanggal
    $tglDari = $dataizin->tgl_izin_dari;
    $tglSampai = $dataizin->tgl_izin_sampai;

    $dDari = date('j', strtotime($tglDari));
    $mDari = $bulanIndo[(int)date('n', strtotime($tglDari))];
    $yDari = date('Y', strtotime($tglDari));
    $hDari = $hariIndo[date('D', strtotime($tglDari))];

    if (empty($tglSampai) || $tglDari == $tglSampai) {
        $teksTanggal = $hDari . ', ' . $dDari . ' ' . $mDari . ' ' . $yDari;
    } else {
        $dSampai = date('j', strtotime($tglSampai));
        $mSampai = $bulanIndo[(int)date('n', strtotime($tglSampai))];
        $ySampai = date('Y', strtotime($tglSampai));
        $hSampai = $hariIndo[date('D', strtotime($tglSampai))];
        $teksTanggal = $hDari . ' s/d ' . $hSampai . ' (' . $dDari . ' ' . $mDari . ' ' . $yDari . ' s/d ' . $dSampai . ' ' . $mSampai . ' ' . $ySampai . ')';
    }

    // Format Jenis Izin
    if ($dataizin->status == 'tl') {
        $jenisIzin = 'tugas luar kantor';
    } elseif ($dataizin->status == 'i') {
        $jenisIzin = 'tidak masuk kerja';
    } elseif ($dataizin->status == 's') {
        $jenisIzin = 'sakit';
    } elseif ($dataizin->status == 'c') {
        $jenisIzin = 'cuti' . (!empty($dataizin->nama_cuti) ? ' ' . strtolower($dataizin->nama_cuti) : '');
    } elseif ($dataizin->status == 'it') {
        $jenisIzin = 'terlambat masuk kerja';
    } elseif ($dataizin->status == 'ipc') {
        $jenisIzin = 'pulang lebih awal';
    } else {
        $jenisIzin = 'tidak masuk kerja';
    }

    $alasan = !empty($dataizin->keterangan) ? $dataizin->keterangan : '-';

    $pesanPermohonan = "Assalamu’alaikum Wr. Wb.\nKepada Yth. Kepala Balai Pelatihan Koperasi & Usaha Kecil Prov. Kalsel\nCq. Kasubbag Tata Usaha\n\nSaya a.n. " . $namaPegawai . " pada hari ini " . $teksTanggal . " memohon izin " . $jenisIzin . " karena " . $alasan . ".\nDemikian pesan ini disampaikan, atas berkanannya diucapkan terimakasi.";
@endphp

<ul class="action-button-list">
    <!-- Tombol Kirim Langsung ke WhatsApp -->
    <li>
        <a href="https://api.whatsapp.com/send?text={{ rawurlencode($pesanPermohonan) }}" target="_blank" class="btn btn-list text-success" style="font-weight: 600;">
            <span>
                <ion-icon name="logo-whatsapp" style="font-size: 22px; color: #25D366;"></ion-icon>
                Kirim via WhatsApp
            </span>
        </a>
    </li>

    @if ($dataizin->status_approved == 0)
        <!-- Tombol Edit (Hanya jika belum disetujui) -->
        <li>
            @if ($dataizin->status == "i" || $dataizin->status == "it" || $dataizin->status == "ipc")
                <a href="/izinabsen/{{ $dataizin->kode_izin }}/edit" class="btn btn-list text-primary">
                    <span>
                        <ion-icon name="create-outline" style="font-size: 22px;"></ion-icon>
                        Edit
                    </span>
                </a>
            @elseif($dataizin->status == "s")
                <a href="/izinsakit/{{ $dataizin->kode_izin }}/edit" class="btn btn-list text-primary">
                    <span>
                        <ion-icon name="create-outline" style="font-size: 22px;"></ion-icon>
                        Edit
                    </span>
                </a>
            @elseif($dataizin->status == "c")
                <a href="/izincuti/{{ $dataizin->kode_izin }}/edit" class="btn btn-list text-primary">
                    <span>
                        <ion-icon name="create-outline" style="font-size: 22px;"></ion-icon>
                        Edit
                    </span>
                </a>
            @endif
        </li>

        <!-- Tombol Delete (Hanya jika belum disetujui) -->
        <li>
            <a href="#" id="deletebutton" class="btn btn-list text-danger" data-dismiss="modal" data-toggle="modal" data-target="#deleteConfirm">
                <span>
                    <ion-icon name="trash-outline" style="font-size: 22px;"></ion-icon>
                    Delete
                </span>
            </a>
        </li>
    @else
        <!-- Keterangan jika sudah disetujui -->
        <li class="text-center py-2">
            <span class="badge bg-success text-white" style="font-size: 11px; padding: 6px 12px; border-radius: 20px;">
                <ion-icon name="checkmark-circle-outline" style="vertical-align: -1px; margin-right: 3px;"></ion-icon>
                Pengajuan sudah disetujui (tidak dapat diubah/dihapus)
            </span>
        </li>
    @endif
</ul>

<script>
    $(function() {
        // Handle Delete Button link
        $("#deletebutton").click(function(e) {
            $("#hapuspengajuan").attr('href', '/izin/' + '{{ $dataizin->kode_izin }}/delete');
        });
    });
</script>
