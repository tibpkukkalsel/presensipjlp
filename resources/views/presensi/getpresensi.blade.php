<?php
if (!function_exists('hitungSelisihAdmin')) {
    function hitungSelisihAdmin($jam_jadwal, $jam_presensi) {
        if (empty($jam_jadwal) || empty($jam_presensi)) return '0 m';
        $pAwal = explode(':', $jam_jadwal);
        $pAkhir = explode(':', $jam_presensi);
        if (count($pAwal) < 2 || count($pAkhir) < 2) return '0 m';
        $tAwal = mktime((int)$pAwal[0], (int)$pAwal[1], (int)($pAwal[2] ?? 0), 1, 1, 1);
        $tAkhir = mktime((int)$pAkhir[0], (int)$pAkhir[1], (int)($pAkhir[2] ?? 0), 1, 1, 1);
        $diffSec = $tAkhir - $tAwal;
        if ($diffSec <= 0) return '0 m';
        $totalMenit = floor($diffSec / 60);
        $jam = floor($totalMenit / 60);
        $menit = $totalMenit % 60;
        if ($jam > 0) {
            return $jam . ' jam ' . $menit . ' mnt';
        }
        return $menit . ' mnt';
    }
}
?>

@forelse ($presensi as $d)
    @php
        $foto_in = !empty($d->foto_in) ? Storage::url('uploads/absensi/' . $d->foto_in) . '?v=' . time() : null;
        $foto_out = !empty($d->foto_out) ? Storage::url('uploads/absensi/' . $d->foto_out) . '?v=' . time() : null;
        
        $ceklibur = !empty($datalibur) ? cekkaryawanlibur($datalibur, ['nik' => $d->nik, 'tanggal_libur' => $tanggal]) : [];
        $is_libur = !empty($ceklibur);
        $keterangan_libur = $is_libur ? ($ceklibur[0]['keterangan'] ?? 'Libur Nasional') : '';
    @endphp
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td><span class="badge bg-light text-dark border">{{ $d->nik }}</span></td>
        <td class="fw-bold">{{ $d->nama_lengkap }}</td>
        <td>{{ $d->nama_dept ?? '-' }}</td>
        <td>{{ $d->lokasi ?? '-' }}</td>
        <td>
            @if (!empty($d->nama_jam_kerja))
                {{ $d->nama_jam_kerja }} ({{ substr($d->jam_masuk, 0, 5) }} s/d {{ substr($d->jam_pulang, 0, 5) }})
            @else
                <span class="text-muted">-</span>
            @endif
        </td>
        <td class="text-center">
            @if ($d->jam_in != null)
                <span class="badge bg-success">{{ substr($d->jam_in, 0, 5) }}</span>
                @if ($d->status_in == 'izin_terlambat')
                    <br><span class="badge bg-warning text-dark mt-1">Izin Terlambat</span>
                @elseif ($d->status_in == 'terlambat' || (!empty($d->jam_masuk) && $d->jam_in > $d->jam_masuk))
                    <br><span class="badge bg-danger mt-1">Terlambat {{ hitungSelisihAdmin($d->jam_masuk, $d->jam_in) }}</span>
                @endif
            @elseif ($is_libur)
                <span class="badge bg-secondary">Libur Nasional</span>
            @else
                <span class="badge bg-danger">Belum Absen</span>
            @endif
        </td>
        <td class="text-center">
            @if ($foto_in != null)
                <img src="{{ url($foto_in) }}" class="avatar" alt="">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-cancel"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 8h.01" />
                    <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                    <path d="M14 14l1 -1c.616 -.593 1.328 -.792 2.008 -.598" />
                    <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M17 21l4 -4" />
                </svg>
            @endif
        </td>
        <td class="text-center">
            @if ($d->jam_out != null)
                <span class="badge bg-info">{{ substr($d->jam_out, 0, 5) }}</span>
                @if ($d->status_out == 'izin_pulang_cepat')
                    <br><span class="badge bg-warning text-dark mt-1">Izin Pulang Cepat</span>
                @elseif ($d->status_out == 'pulang_duluan' || (!empty($d->jam_pulang) && $d->jam_out < $d->jam_pulang))
                    <br><span class="badge bg-danger mt-1">Pulang Duluan {{ hitungSelisihAdmin($d->jam_out, $d->jam_pulang) }}</span>
                @endif
            @elseif ($is_libur)
                <span class="badge bg-secondary">Libur Nasional</span>
            @else
                <span class="badge bg-danger">Belum Absen</span>
            @endif
        </td>
        <td class="text-center">
            @if ($foto_out != null)
                <img src="{{ url($foto_out) }}" class="avatar" alt="">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-cancel"
                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M15 8h.01" />
                    <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3 3" />
                    <path d="M14 14l1 -1c.616 -.593 1.328 -.792 2.008 -.598" />
                    <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M17 21l4 -4" />
                </svg>
            @endif
        </td>
        <td class="text-center">
            @if ($d->status == 'h')
                <span class="badge bg-success">Hadir</span>
                @if ($d->status_in == 'izin_terlambat')
                    <br><span class="badge bg-warning text-dark mt-1">Izin Terlambat</span>
                @elseif ($d->status_in == 'terlambat')
                    <br><span class="badge bg-danger mt-1">Terlambat</span>
                @endif
                @if ($d->status_out == 'izin_pulang_cepat')
                    <br><span class="badge bg-warning text-dark mt-1">Izin Pulang Cepat</span>
                @elseif ($d->status_out == 'pulang_duluan')
                    <br><span class="badge bg-danger mt-1">Pulang Duluan</span>
                @endif
            @elseif ($d->status == 'tl')
                <span class="badge text-white" style="background-color: #20c997;">Tugas Luar</span>
            @elseif ($d->status == 'i')
                <span class="badge bg-warning text-dark">Izin</span>
            @elseif ($d->status == 's')
                <span class="badge bg-info">Sakit</span>
            @elseif ($d->status == 'c')
                <span class="badge bg-primary">Cuti</span>
            @elseif ($d->status == 'l' || $is_libur)
                <span class="badge bg-secondary" title="{{ $keterangan_libur }}">Cuti/Libur Nasional</span>
            @elseif ($d->status == 'a')
                <span class="badge bg-danger">Alfa</span>
            @else
                @if ($is_libur)
                    <span class="badge bg-secondary" title="{{ $keterangan_libur }}">Cuti/Libur Nasional</span>
                @else
                    <span class="badge bg-danger">Belum Absen</span>
                @endif
            @endif
        </td>
        <td class="text-center">
            <div class="d-flex align-items-center justify-content-center gap-1">
                @if ($d->lokasi_in != null)
                    <a href="#" class="btn btn-primary btn-sm btn-action tampilkanpeta" id="{{ $d->id }}" title="Lihat Peta Lokasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-2"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M18 6l0 .01"></path>
                            <path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"></path>
                            <path d="M10.5 4.75l-1.5 -.75l-6 3l0 13l6 -3l6 3l6 -3l0 -2"></path>
                            <path d="M9 4l0 13"></path>
                            <path d="M15 15l0 5"></path>
                        </svg>
                    </a>
                @endif
                <a href="#" class="btn btn-success btn-sm btn-action koreksipresensi" nik="{{ $d->nik }}" title="Koreksi Presensi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                        <path d="M16 5l3 3"></path>
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12" class="text-center py-4 text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle mb-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/></svg>
            <br>
            Tidak ada data presensi / pegawai untuk tanggal dan filter yang dipilih.
        </td>
    </tr>
@endforelse

<script>
    $(function() {
        $(".tampilkanpeta").click(function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $("#loadmap").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Memuat peta...</div></div>');
            $.ajax({
                type: 'POST',
                url: '/panel-adm14/tampilkanpeta',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond) {
                    $("#loadmap").html(respond);
                },
                error: function(xhr, status, error) {
                    $("#loadmap").html('<div class="alert alert-danger m-3">Gagal memuat peta lokasi presensi.</div>');
                }
            });
            $("#modal-tampilkanpeta").modal("show");
        });

        $(".koreksipresensi").click(function(e) {
            e.preventDefault();
            var nik = $(this).attr("nik");
            var tanggal = "{{ $tanggal }}";
            $("#loadkoreksipresensi").html('<div class="text-center py-4"><div class="spinner-border text-success" role="status"></div><div class="mt-2 text-muted">Memuat form koreksi...</div></div>');
            $("#modal-koreksipresensi").modal("show");
            $.ajax({
                type: 'POST',
                url: '/panel-adm14/koreksipresensi',
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    $("#loadkoreksipresensi").html(respond);
                },
                error: function(xhr, status, error) {
                    $("#loadkoreksipresensi").html('<div class="alert alert-danger m-3">Gagal memuat form koreksi presensi.</div>');
                }
            });
        });
    });
</script>
