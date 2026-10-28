@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Status Kehadiran Pegawai
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Alert Messages -->
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ Session::get('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            {{ Session::get('warning') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Beri Status Kehadiran -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" id="btnTambahStatus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>
                                        Buat
                                    </button>
                                </div>
                            </div>

                            <!-- Form Filter Pencarian -->
                            <div class="row mt-2">
                                <div class="col-12">
                                    <form action="/panel-adm14/presensi/statuskehadiran" method="GET">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Dari Tanggal</label>
                                                <input type="date" name="dari" id="dari" class="form-control"
                                                    value="{{ $dari }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Sampai Tanggal</label>
                                                <input type="date" name="sampai" id="sampai" class="form-control"
                                                    value="{{ $sampai }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Nama / NIK Pegawai</label>
                                                <input type="text" name="nama_pegawai" id="nama_pegawai"
                                                    class="form-control" placeholder="Cari Pegawai..."
                                                    value="{{ Request('nama_pegawai') }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Seksi</label>
                                                <select name="kode_dept" id="filter_kode_dept" class="form-select">
                                                    <option value="">Semua Seksi</option>
                                                    @foreach ($departemen as $d)
                                                        <option value="{{ $d->kode_dept }}"
                                                            {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                                            {{ $d->nama_dept }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label mb-1">Status Kehadiran</label>
                                                <select name="status_filter" id="status_filter" class="form-select">
                                                    <option value="">Semua Status</option>
                                                    <option value="h"
                                                        {{ Request('status_filter') == 'h' ? 'selected' : '' }}>Hadir (Tepat
                                                        Waktu)</option>
                                                    <option value="terlambat"
                                                        {{ Request('status_filter') == 'terlambat' ? 'selected' : '' }}>
                                                        Terlambat</option>
                                                    <option value="pulang_duluan"
                                                        {{ Request('status_filter') == 'pulang_duluan' ? 'selected' : '' }}>
                                                        Pulang Duluan</option>
                                                    <option value="tl"
                                                        {{ Request('status_filter') == 'tl' ? 'selected' : '' }}>Tugas Luar
                                                    </option>
                                                    <option value="it"
                                                        {{ Request('status_filter') == 'it' ? 'selected' : '' }}>Izin
                                                        Terlambat</option>
                                                    <option value="ipc"
                                                        {{ Request('status_filter') == 'ipc' ? 'selected' : '' }}>Izin
                                                        Pulang Cepat</option>
                                                    <option value="i"
                                                        {{ Request('status_filter') == 'i' ? 'selected' : '' }}>Izin
                                                    </option>
                                                    <option value="s"
                                                        {{ Request('status_filter') == 's' ? 'selected' : '' }}>Sakit
                                                    </option>
                                                    <option value="c"
                                                        {{ Request('status_filter') == 'c' ? 'selected' : '' }}>Cuti
                                                    </option>
                                                    <option value="l"
                                                        {{ Request('status_filter') == 'l' ? 'selected' : '' }}>Libur
                                                        Nasional</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-search" width="20"
                                                        height="20" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                        <path d="M21 21l-6 -6"></path>
                                                    </svg>
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Tabel Data Status Kehadiran -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">No</th>
                                                    <th style="width: 95px;" class="text-center">Tanggal</th>
                                                    <th>Pegawai</th>
                                                    <th>Seksi</th>
                                                    <th>Jadwal Kerja</th>
                                                    <th style="width: 130px;" class="text-center">Jam Masuk</th>
                                                    <th style="width: 130px;" class="text-center">Jam Pulang</th>
                                                    <th style="width: 130px;" class="text-center">Status</th>
                                                    <th>Keterangan</th>
                                                    <th style="width: 90px;" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($datapresensi as $d)
                                                    @php
                                                        // Tentukan badge status utama
                                                        $statusBadge = 'bg-secondary';
                                                        $statusLabel = strtoupper($d->status);

                                                        if ($d->status == 'h') {
                                                            if ($d->status_in == 'izin_terlambat' && $d->status_out == 'izin_pulang_cepat') {
                                                                $statusBadge = 'bg-primary text-white';
                                                                $statusLabel = 'Izin Terlambat & Pulang Cepat';
                                                            } elseif ($d->status_in == 'izin_terlambat') {
                                                                $statusBadge = 'bg-info text-dark';
                                                                $statusLabel = 'Izin Terlambat';
                                                            } elseif ($d->status_out == 'izin_pulang_cepat') {
                                                                $statusBadge = 'bg-primary';
                                                                $statusLabel = 'Izin Pulang Cepat';
                                                            } elseif ($d->status_in == 'terlambat') {
                                                                $statusBadge = 'bg-danger';
                                                                $statusLabel = 'Terlambat';
                                                            } elseif ($d->status_out == 'pulang_duluan') {
                                                                $statusBadge = 'bg-warning text-dark';
                                                                $statusLabel = 'Pulang Duluan';
                                                            } else {
                                                                $statusBadge = 'bg-success';
                                                                $statusLabel = 'Hadir';
                                                            }
                                                        } elseif ($d->status == 'tl') {
                                                            $statusBadge = 'bg-teal text-white';
                                                            $statusLabel = 'Tugas Luar';
                                                        } elseif ($d->status == 'i') {
                                                            $statusBadge = 'bg-warning text-dark';
                                                            $statusLabel = 'Izin';
                                                        } elseif ($d->status == 's') {
                                                            $statusBadge = 'bg-danger';
                                                            $statusLabel = 'Sakit';
                                                        } elseif ($d->status == 'c') {
                                                            $statusBadge = 'bg-primary';
                                                            $statusLabel = 'Cuti';
                                                        } elseif ($d->status == 'l') {
                                                            $statusBadge = 'bg-dark';
                                                            $statusLabel = 'Libur Nasional';
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $loop->iteration + $datapresensi->firstItem() - 1 }}</td>
                                                        <td class="text-center fw-semibold">
                                                            {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <div class="fw-bold">{{ $d->nama_lengkap }}</div>
                                                                    <div class="text-muted small">
                                                                        <span
                                                                            class="badge bg-light text-dark border">{{ $d->nik }}</span>
                                                                        @if (!empty($d->jabatan))
                                                                            &bull; {{ $d->jabatan }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $d->nama_dept ?? '-' }}</td>
                                                        <td>
                                                            @if (!empty($d->nama_jam_kerja))
                                                                <span class="badge bg-light text-dark border">
                                                                    {{ $d->nama_jam_kerja }}
                                                                </span>
                                                                <div class="small text-muted">
                                                                    {{ date('H:i', strtotime($d->jam_masuk_jadwal)) }} -
                                                                    {{ date('H:i', strtotime($d->jam_pulang_jadwal)) }}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if (!empty($d->jam_in))
                                                                <div class="fw-bold">{{ $d->jam_in }}</div>
                                                                @if ($d->status_in == 'izin_terlambat')
                                                                    <span class="badge bg-info text-dark"
                                                                        style="font-size: 0.72rem;">Izin Terlambat</span>
                                                                @elseif ($d->status_in == 'terlambat')
                                                                    <span class="badge bg-danger"
                                                                        style="font-size: 0.72rem;">
                                                                        Terlambat
                                                                        {{ $d->selisih_in > 0 ? $d->selisih_in . 'm' : '' }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-success"
                                                                        style="font-size: 0.72rem;">Tepat Waktu</span>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if (!empty($d->jam_out))
                                                                <div class="fw-bold">{{ $d->jam_out }}</div>
                                                                @if ($d->status_out == 'izin_pulang_cepat')
                                                                    <span class="badge bg-primary"
                                                                        style="font-size: 0.72rem;">Izin Pulang
                                                                        Cepat</span>
                                                                @elseif ($d->status_out == 'pulang_duluan')
                                                                    <span class="badge bg-warning text-dark"
                                                                        style="font-size: 0.72rem;">
                                                                        Pulang Awal
                                                                        {{ $d->selisih_out > 0 ? $d->selisih_out . 'm' : '' }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-success"
                                                                        style="font-size: 0.72rem;">Tepat Waktu</span>
                                                                @endif
                                                            @else
                                                                @if ($d->status == 'h')
                                                                    <span class="badge bg-secondary"
                                                                        style="font-size: 0.72rem;">Belum Pulang</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $statusBadge }} fs-7 px-2 py-1">
                                                                {{ $statusLabel }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if (!empty($d->keterangan_izin))
                                                                <span class="small">{{ $d->keterangan_izin }}</span>
                                                            @elseif ($d->lokasi_in == 'Ditetapkan oleh Admin')
                                                                <span class="small text-muted font-italic">Input Manual
                                                                    Admin</span>
                                                            @else
                                                                <span class="small text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                class="d-flex align-items-center justify-content-center gap-1">
                                                                <!-- Tombol Edit -->
                                                                <button type="button"
                                                                    class="btn btn-info btn-sm btn-action btn-edit-status"
                                                                    data-id="{{ $d->id }}"
                                                                    title="Edit Status Kehadiran">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-edit"
                                                                        viewBox="0 0 24 24" stroke-width="2"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none"></path>
                                                                        <path
                                                                            d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                                        </path>
                                                                        <path
                                                                            d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                                        </path>
                                                                        <path d="M16 5l3 3"></path>
                                                                    </svg>
                                                                </button>

                                                                <!-- Tombol Hapus -->
                                                                <form
                                                                    action="/panel-adm14/presensi/{{ $d->id }}/deletestatuskehadiran"
                                                                    method="POST" class="m-0 d-inline-block">
                                                                    @csrf
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-action delete-confirm"
                                                                        title="Hapus Presensi">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-trash-filled"
                                                                            viewBox="0 0 24 24" stroke-width="2"
                                                                            stroke="currentColor" fill="none"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none"></path>
                                                                            <path
                                                                                d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z"
                                                                                stroke-width="0" fill="currentColor">
                                                                            </path>
                                                                            <path
                                                                                d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z"
                                                                                stroke-width="0" fill="currentColor">
                                                                            </path>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center py-3 text-muted">
                                                            Data presensi tidak ditemukan pada rentang tanggal ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $datapresensi->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Status Kehadiran -->
    <div class="modal modal-blur fade" id="modal-inputstatus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Status Kehadiran Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/panel-adm14/presensi/storestatuskehadiran" method="POST" id="frmStatusKehadiran">
                        @csrf
                        <!-- Pilih Pegawai -->
                        <div class="mb-3">
                            <label class="form-label required">Pegawai</label>
                            <select name="nik" id="nik" class="form-select" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach ($listPegawai as $p)
                                    <option value="{{ $p->nik }}">
                                        {{ $p->nik }} - {{ $p->nama_lengkap }} ({{ $p->jabatan ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rentang Tanggal -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label required">Dari Tanggal</label>
                                <input type="date" name="tgl_dari" id="tgl_dari" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label required">Sampai Tanggal</label>
                                <input type="date" name="tgl_sampai" id="tgl_sampai" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Pilihan Status Kehadiran -->
                        <div class="mb-3">
                            <label class="form-label required">Status Kehadiran</label>
                            <select name="status_pilihan" id="status_pilihan" class="form-select" required>
                                <option value="tl">Tugas Luar</option>
                                <option value="it">Izin Terlambat</option>
                                <option value="ipc">Izin Pulang Cepat</option>
                                <option value="i">Izin</option>
                                <option value="s">Sakit</option>
                                <option value="c">Cuti</option>
                                <option value="l">Libur Nasional</option>
                            </select>
                        </div>

                        <!-- Keterangan / Alasan -->
                        <div class="mb-3">
                            <label class="form-label">Keterangan / Alasan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy"
                                    width="20" height="20" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                    <path d="M14 4l0 4l-6 0l0 -4"></path>
                                </svg>
                                Simpan Status Kehadiran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Status Kehadiran -->
    <div class="modal modal-blur fade" id="modal-editstatus" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Status Kehadiran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/panel-adm14/presensi/updatestatuskehadiran" method="POST"
                        id="frmEditStatusKehadiran">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label class="form-label">Pegawai</label>
                            <input type="text" id="edit_nama_pegawai" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Presensi</label>
                            <input type="text" id="edit_tgl_presensi" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status Kehadiran</label>
                            <select name="status_pilihan" id="edit_status_pilihan" class="form-select" required>
                                <option value="tl">Tugas Luar</option>
                                <option value="it">Izin Terlambat</option>
                                <option value="ipc">Izin Pulang Cepat</option>
                                <option value="i">Izin</option>
                                <option value="s">Sakit</option>
                                <option value="c">Cuti</option>
                                <option value="l">Libur Nasional</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan / Alasan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check"
                                    width="20" height="20" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                                Perbarui Status Kehadiran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Trigger Modal Input Status
            $('#btnTambahStatus').click(function() {
                $('#frmStatusKehadiran')[0].reset();
                $('#tgl_dari').val('{{ date('Y-m-d') }}');
                $('#tgl_sampai').val('{{ date('Y-m-d') }}');
                $('#status_pilihan').trigger('change');
                $('#modal-inputstatus').modal('show');
            });

            // Sinkronisasi rentang tanggal jika dari > sampai
            $('#tgl_dari').change(function() {
                var dari = $(this).val();
                var sampai = $('#tgl_sampai').val();
                if (sampai < dari) {
                    $('#tgl_sampai').val(dari);
                }
            });

            // Klik Tombol Edit
            $('.btn-edit-status').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: '/panel-adm14/presensi/editstatuskehadiran',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res) {
                            $('#edit_id').val(res.id);
                            $('#edit_nama_pegawai').val(res.nik + ' - ' + res.nama_lengkap);
                            $('#edit_tgl_presensi').val(res.tgl_presensi);

                            var stVal = res.status;
                            if (res.status === 'h') {
                                if (res.status_in === 'izin_terlambat') {
                                    stVal = 'it';
                                } else if (res.status_out === 'izin_pulang_cepat') {
                                    stVal = 'ipc';
                                } else {
                                    stVal = 'it';
                                }
                            }
                            $('#edit_status_pilihan').val(stVal);
                            $('#edit_keterangan').val(res.keterangan_izin || '');

                            $('#modal-editstatus').modal('show');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil data status kehadiran.',
                            icon: 'error'
                        });
                    }
                });
            });

            // SweetAlert Konfirmasi Hapus
            $('.delete-confirm').click(function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data presensi ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus Data!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Validasi Form Tambah
            $('#frmStatusKehadiran').submit(function() {
                var nik = $('#nik').val();
                var tgl_dari = $('#tgl_dari').val();
                var st = $('#status_pilihan').val();

                if (nik === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Silakan pilih pegawai terlebih dahulu.',
                        icon: 'warning'
                    });
                    return false;
                }
                if (tgl_dari === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Tanggal harus diisi.',
                        icon: 'warning'
                    });
                    return false;
                }
                if (st === '') {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Status kehadiran harus dipilih.',
                        icon: 'warning'
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
