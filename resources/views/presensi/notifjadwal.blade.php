@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <div style="padding-top: 75px; padding-bottom: 30px;">
        <div class="row">
            <div class="col-12 px-3">
                <div class="card text-center" style="border-radius: 18px; border: 1px solid #fef3c7; box-shadow: 0 4px 16px rgba(0,0,0,0.06); padding: 20px 15px; background: #ffffff;">
                    <div class="card-body">
                        <div style="width: 72px; height: 72px; border-radius: 50%; background: #fef3c7; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <ion-icon name="time-outline" style="font-size: 40px; color: #f59e0b;"></ion-icon>
                        </div>
                        <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">Tidak Ada Jadwal!</h3>
                        <p style="color: #64748b; font-size: 0.92rem; line-height: 1.5; margin-bottom: 20px;">
                            Maaf, Anda Tidak Memiliki Jadwal Pada Hari ini, Silahkan Hubungi Admin Presis
                        </p>
                        <div class="mt-2">
                            <a href="/dashboard" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 600; height: 44px; display: flex; align-items: center; justify-content: center;">
                                <ion-icon name="arrow-back-outline" style="margin-right: 6px; font-size: 18px;"></ion-icon>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
