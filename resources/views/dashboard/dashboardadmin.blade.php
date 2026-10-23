@extends('layouts.admin.tabler')

@section('content')
@php
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n')];
    $tanggal_indo = $hari . ', ' . date('j') . ' ' . $bulan . ' ' . date('Y');

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
                return $jam . 'j ' . $menit . 'm';
            }
            return $menit . ' m';
        }
    }
@endphp

    <!-- Content Header (Page header) -->
    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <h3 class="m-0 fw-bold text-dark">Dashboard</h3>
            <small class="text-muted">Rekapitulasi Presensi Pegawai &bull; {{ $tanggal_indo }}</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item"><a href="/panel-adm14/dashboardadmin"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
    </div>

    <!-- Small boxes (Stat box) - Standard AdminLTE -->
    <div class="row">
        <!-- Box 1: Jumlah Pegawai -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalpegawai }}</h3>
                    <p>Jumlah Pegawai</p>
                </div>
                <i class="small-box-icon bi bi-people-fill"></i>
                <a href="/panel-adm14/pegawai" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Data Pegawai <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Box 2: Pegawai Hadir -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $jmlhadir }}</h3>
                    <p>Pegawai Hadir</p>
                </div>
                <i class="small-box-icon bi bi-fingerprint"></i>
                <a href="/panel-adm14/presensi/monitoring" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Monitoring Presensi <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Box 3: Pegawai Izin -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $jmlizin }}</h3>
                    <p>Pegawai Izin / Sakit</p>
                </div>
                <i class="small-box-icon bi bi-file-earmark-medical-fill"></i>
                <a href="/panel-adm14/presensi/izinsakit" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    Data Izin & Sakit <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>

        <!-- Box 4: Belum Absen -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $jmlbelumabsen }}</h3>
                    <p>Belum Absen</p>
                </div>
                <i class="small-box-icon bi bi-clock-history"></i>
                <a href="/panel-adm14/presensi/monitoring" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Monitoring Presensi <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->

    <!-- Main Table Row - Standard AdminLTE Card & Table -->
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title fw-bold">
                        <i class="bi bi-card-checklist me-2"></i>Daftar Pegawai yang Mengabsen Hari Ini
                    </h3>
                    <div class="card-tools">
                        <span class="badge text-bg-primary">{{ count($datapresensi) }} Data Presensi</span>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 45px;">No.</th>
                                <th>NIK & Nama Pegawai</th>
                                <th>Seksi</th>
                                <th>Lokasi Kantor</th>
                                <th>Jadwal Kerja</th>
                                <th class="text-center" style="width: 110px;">Jam Masuk</th>
                                <th class="text-center" style="width: 75px;">Foto Masuk</th>
                                <th class="text-center" style="width: 110px;">Jam Pulang</th>
                                <th class="text-center" style="width: 75px;">Foto Pulang</th>
                                <th class="text-center" style="width: 85px;">Status</th>
                                <th class="text-center" style="width: 65px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($datapresensi as $d)
                                @php
                                    $foto_pegawai_path = !empty($d->foto_pegawai) ? Storage::url('uploads/karyawan/' . $d->foto_pegawai) : null;
                                    $foto_in_path = !empty($d->foto_in) ? Storage::url('uploads/absensi/' . $d->foto_in) : null;
                                    $foto_out_path = !empty($d->foto_out) ? Storage::url('uploads/absensi/' . $d->foto_out) : null;
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($foto_pegawai_path)
                                                <img src="{{ url($foto_pegawai_path) }}" class="rounded-circle me-2 border" style="width: 38px; height: 38px; object-fit: cover;" alt="{{ $d->nama_lengkap }}">
                                            @else
                                                <img src="{{ asset('assets/img/nophoto.png') }}" class="rounded-circle me-2 border" style="width: 38px; height: 38px; object-fit: cover;" alt="No Photo">
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark">{{ $d->nama_lengkap }}</div>
                                                <small class="text-muted">{{ $d->nik }} @if(!empty($d->jabatan)) &bull; {{ $d->jabatan }} @endif</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $d->nama_dept ?? '-' }}</td>
                                    <td>
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $d->nama_cabang ?? '-' }}
                                    </td>
                                    <td>
                                        @if (!empty($d->nama_jam_kerja))
                                            <span class="badge text-bg-light border">{{ $d->nama_jam_kerja }}</span>
                                            <small class="text-muted d-block">{{ substr($d->jam_masuk_jadwal, 0, 5) }} - {{ substr($d->jam_pulang_jadwal, 0, 5) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($d->jam_in))
                                            <span class="badge text-bg-success">{{ substr($d->jam_in, 0, 5) }}</span>
                                            @if ($d->status_in == 'izin_terlambat')
                                                <small class="badge text-bg-warning text-dark d-block mt-1">Izin Terlambat</small>
                                            @elseif ($d->status_in == 'terlambat' || (!empty($d->jam_masuk_jadwal) && $d->jam_in > $d->jam_masuk_jadwal))
                                                <small class="text-danger d-block mt-1">Telat {{ hitungSelisihAdmin($d->jam_masuk_jadwal, $d->jam_in) }}</small>
                                            @else
                                                <small class="text-success d-block mt-1">Tepat Waktu</small>
                                            @endif
                                        @else
                                            <span class="badge text-bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($foto_in_path)
                                            <a href="{{ url($foto_in_path) }}" target="_blank" title="Lihat Foto Masuk">
                                                <img src="{{ url($foto_in_path) }}" class="img-thumbnail" style="width: 42px; height: 42px; object-fit: cover;" alt="Foto Masuk">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($d->jam_out))
                                            <span class="badge text-bg-info">{{ substr($d->jam_out, 0, 5) }}</span>
                                            @if ($d->status_out == 'izin_pulang_cepat')
                                                <small class="badge text-bg-warning text-dark d-block mt-1">Izin Pulang Cepat</small>
                                            @elseif ($d->status_out == 'pulang_duluan' || (!empty($d->jam_pulang_jadwal) && $d->jam_out < $d->jam_pulang_jadwal))
                                                <small class="text-danger d-block mt-1">Pulang Awal {{ hitungSelisihAdmin($d->jam_out, $d->jam_pulang_jadwal) }}</small>
                                            @endif
                                        @else
                                            <span class="badge text-bg-warning">Belum Pulang</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($foto_out_path)
                                            <a href="{{ url($foto_out_path) }}" target="_blank" title="Lihat Foto Pulang">
                                                <img src="{{ url($foto_out_path) }}" class="img-thumbnail" style="width: 42px; height: 42px; object-fit: cover;" alt="Foto Pulang">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($d->status == 'h')
                                            <span class="badge text-bg-success">Hadir</span>
                                            @if ($d->status_in == 'izin_terlambat')
                                                <small class="badge text-bg-warning text-dark d-block mt-1">Izin Terlambat</small>
                                            @elseif ($d->status_in == 'terlambat')
                                                <small class="badge text-bg-danger d-block mt-1">Terlambat</small>
                                            @endif
                                            @if ($d->status_out == 'izin_pulang_cepat')
                                                <small class="badge text-bg-warning text-dark d-block mt-1">Izin Pulang Cepat</small>
                                            @elseif ($d->status_out == 'pulang_duluan')
                                                <small class="badge text-bg-danger d-block mt-1">Pulang Duluan</small>
                                            @endif
                                        @elseif ($d->status == 'tl')
                                            <span class="badge text-white" style="background-color: #20c997;">Tugas Luar</span>
                                        @elseif ($d->status == 'i')
                                            <span class="badge text-bg-warning">Izin</span>
                                        @elseif ($d->status == 's')
                                            <span class="badge text-bg-info">Sakit</span>
                                        @elseif ($d->status == 'c')
                                            <span class="badge text-bg-primary">Cuti</span>
                                        @elseif ($d->status == 'l')
                                            <span class="badge text-bg-secondary">Libur Nasional</span>
                                        @else
                                            <span class="badge text-bg-secondary">{{ strtoupper($d->status) }}</span>
                                        @endif

                                        @if (!empty($d->keterangan_izin))
                                            <small class="text-muted d-block mt-1" title="{{ $d->keterangan_izin }}">
                                                {{ \Illuminate\Support\Str::limit($d->keterangan_izin, 18) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!empty($d->lokasi_in))
                                            <button type="button" class="btn btn-primary btn-sm btn-action tampilkanpeta" id="{{ $d->id }}" title="Lihat Peta Lokasi">
                                                <i class="bi bi-geo-alt-fill"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                                        <strong>Belum Ada Data Presensi</strong><br>
                                        <small>Belum ada pegawai yang melakukan absensi hari ini ({{ $tanggal_indo }}).</small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    <!-- Modal Tampilkan Peta Lokasi Presensi (AdminLTE Modal) -->
    <div class="modal fade" id="modal-tampilkanpeta" tabindex="-1" aria-labelledby="modalPetaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPetaLabel">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>Lokasi Presensi Pegawai
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="loadmap">
                    <!-- Leaflet map -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $(document).on('click', '.tampilkanpeta', function(e) {
                e.preventDefault();
                var id = $(this).attr("id");
                $("#loadmap").html('<div class="p-4 text-center"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted small">Memuat peta lokasi...</div></div>');
                $("#modal-tampilkanpeta").modal("show");

                $.ajax({
                    type: 'POST',
                    url: '/tampilkanpeta',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    cache: false,
                    success: function(respond) {
                        $("#loadmap").html(respond);
                    },
                    error: function() {
                        $("#loadmap").html('<div class="alert alert-danger m-3">Gagal memuat peta presensi.</div>');
                    }
                });
            });
        });
    </script>
@endpush
