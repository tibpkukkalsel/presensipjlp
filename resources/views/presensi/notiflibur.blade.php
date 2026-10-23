@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col-12 px-3">
            <div class="card text-center" style="border-radius: 16px; border: 1px solid #fee2e2; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 15px;">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; border-radius: 50%; background: #ffe4e6; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                        <ion-icon name="calendar" style="font-size: 38px; color: #e11d48;"></ion-icon>
                    </div>
                    <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">Hari Ini Libur!</h3>
                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 12px;">
                        Maaf, Anda tidak dapat melakukan presensi karena hari ini adalah hari libur:
                    </p>
                    <div class="badge bg-danger text-white py-2 px-3 mb-2" style="font-size: 0.85rem; border-radius: 8px; white-space: normal; line-height: 1.4;">
                        {{ !empty($datalibur->keterangan) ? $datalibur->keterangan : 'Libur Nasional / Bersama' }}
                    </div>
                    <div class="mt-4">
                        <a href="/dashboard" class="btn btn-primary w-100" style="border-radius: 10px;">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
