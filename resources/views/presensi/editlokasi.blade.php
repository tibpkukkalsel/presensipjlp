@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Ubah Lokasi Absen</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection

@section('content')

<form action="/presensi/{{ $karyawan->nik }}/updatelokasi" method="POST" enctype="multipart/form-data" style="margin-top:70px">
    @csrf
    <div class="col">
        @php
        $messagesuccess = Session::get('success');
        $messageerror = Session::get('error');
        @endphp
        @if (Session::get('success'))
        <div class="alert alert-success">
            {{ $messagesuccess }}
        </div>
        @endif
        @if (Session::get('error'))
        <div class="alert alert-warning">
            {{ $messageerror }}
        </div>
        @endif

        @error('foto')
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
        </div>
        @enderror
        <div class="form-group boxed">
            <div class="form-group">
                <select name="kode_cabang" id="kode_cabang" class="form-control selectmaterialize">
                <option value=""></option>
              @foreach ($cabang as $d)
                    <option {{ $karyawan->kode_cabang == $d->kode_cabang ? 'selected' : '' }}
                        value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                @endforeach
                                </select>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-primary btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
