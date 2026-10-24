@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pilih Jam Kerja</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection
@section('content')
    <div style="padding-top: 75px; padding-bottom: 30px;">
        <div class="row">
            <div class="col-12 px-3">
                @foreach ($jamkerja as $d)
                    <a href="/presensi/{{ Crypt::encrypt($d->kode_jam_kerja) }}/create"
                        style="text-decoration: none; color:black">
                        <div class="card mb-2" style="border: 1px solid #2A8BF2; border-radius: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="finger-print-outline" style="font-size: 42px;"
                                            class="text-success"></ion-icon>
                                    </div>
                                    <div class="datapresensi">
                                        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 6px;">{{ $d->nama_jam_kerja }}</h3>
                                        <span style="font-size: 13px; color: #475569;">Jam Masuk : {{ $d->jam_masuk }}</span><br>
                                        <span style="font-size: 13px; color: #475569;">Jam Pulang : {{ $d->jam_pulang }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
