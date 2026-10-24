@extends('layouts.admin.tabler')

@section('content')
@php
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][date('w')];
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][date('n')];
    $tanggal_indo = $hari . ', ' . date('j') . ' ' . $bulan . ' ' . date('Y');
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

@endsection

