@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Konfigurasi Scan Wajah Presensi
                </h2>
                <div class="text-muted mt-1" style="font-size: 13px;">
                    Kelola pengaktifan sensor pemindaian wajah (kedip & senyum) atau mode tombol foto manual untuk perangkat pegawai.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ Session::get('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::get('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ Session::get('warning') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Card Master Switch Global -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h4 class="card-title mb-0 fw-bold">Status Fitur Scan Wajah (Global)</h4>
                                    @if ($config->status_scan == 1)
                                        <span class="badge bg-success text-white px-2 py-1" style="font-size: 11px;">AKTIF (ON)</span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1" style="font-size: 11px;">NONAKTIF (OFF)</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0" style="font-size: 13px; max-width: 700px;">
                                    @if ($config->status_scan == 1)
                                        Fitur scan wajah otomatis (kedip &amp; senyum) saat ini <strong>Aktif</strong>. Pegawai diarahkan melakukan deteksi liveness sebelum foto tersimpan otomatis.
                                    @else
                                        Fitur scan wajah saat ini <strong>Dinonaktifkan</strong>. Halaman presensi pegawai beralih ke <strong>Mode Tombol Foto Manual</strong> (tanpa proses sensor AI yang berat), cocok untuk perangkat/HP spesifikasi rendah.
                                    @endif
                                </p>
                            </div>
                            <div>
                                @if ($config->status_scan == 1)
                                    <a href="/panel-adm14/konfigurasi/togglescan" class="btn btn-outline-danger px-4 py-2"
                                       onclick="return confirm('Nonaktifkan fitur scan wajah secara global? Pegawai akan menggunakan tombol foto manual.')"
                                       style="border-radius: 10px; font-weight: 600;">
                                        <i class="bi bi-power me-1"></i> Matikan Scan Wajah
                                    </a>
                                @else
                                    <a href="/panel-adm14/konfigurasi/togglescan" class="btn btn-success px-4 py-2"
                                       onclick="return confirm('Aktifkan kembali fitur scan wajah secara global?')"
                                       style="border-radius: 10px; font-weight: 600;">
                                        <i class="bi bi-camera me-1"></i> Aktifkan Scan Wajah
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Pengaturan Per-Pegawai -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-header bg-white py-3">
                        <h4 class="card-title fw-bold mb-0">Pengaturan Mode Scan Per-Pegawai</h4>
                        <div class="text-muted mt-1" style="font-size: 12px;">
                            Jika ada pegawai tertentu yang perangkat HP-nya lemot atau tidak sanggup memindai wajah, Anda dapat menonaktifkan scan khusus untuk pegawai tersebut di bawah ini.
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th style="width: 110px;">ID Pegawai</th>
                                    <th>Nama Pegawai</th>
                                    <th>Seksi / Bagian</th>
                                    <th>Jabatan</th>
                                    <th style="width: 140px;" class="text-center">Status Scan Pegawai</th>
                                    <th style="width: 130px;" class="text-center">Aksi Toggle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pegawai as $p)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $p->nik }}</span></td>
                                        <td class="fw-bold">{{ $p->nama_lengkap }}</td>
                                        <td>{{ $p->nama_dept ?? '-' }}</td>
                                        <td>{{ $p->jabatan }}</td>
                                        <td class="text-center">
                                            @if (($p->status_scan ?? '1') == '1')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">
                                                    <i class="bi bi-camera-fill me-1"></i> Scan Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 11px;">
                                                    <i class="bi bi-hand-index-thumb me-1"></i> Tombol Foto Manual
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if (($p->status_scan ?? '1') == '1')
                                                <a href="/panel-adm14/pegawai/{{ $p->nik }}/lockandunlockscan"
                                                   class="btn btn-sm btn-outline-danger"
                                                   title="Ubah ke Mode Tombol Foto Manual"
                                                   style="border-radius: 8px; font-size: 11px;">
                                                    Set Tombol Foto
                                                </a>
                                            @else
                                                <a href="/panel-adm14/pegawai/{{ $p->nik }}/lockandunlockscan"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="Ubah ke Mode Scan Wajah"
                                                   style="border-radius: 8px; font-size: 11px;">
                                                    Set Scan Wajah
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
