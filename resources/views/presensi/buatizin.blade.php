@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal {
        max-height: 430px !important;
    }

    .datepicker-date-display {
        background-color: #0f3a7e !important;
    }

    /* Proteksi presisi header & tombol kembali terhadap conflict CSS Materialize */
    .appHeader .left {
        position: absolute !important;
        left: 10px !important;
        top: 0 !important;
        float: none !important;
        height: 56px !important;
        display: flex !important;
        align-items: center !important;
        z-index: 10 !important;
    }

    .appHeader .left .headerButton {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 56px !important;
        min-width: 36px !important;
        padding: 0 10px !important;
        color: #ffffff !important;
    }

    .appHeader .left .headerButton ion-icon {
        font-size: 26px !important;
        color: #ffffff !important;
    }

    .appHeader .pageTitle {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .appHeader .right {
        position: absolute !important;
        right: 10px !important;
        top: 0 !important;
        float: none !important;
        height: 56px !important;
        display: flex !important;
        align-items: center !important;
    }
</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="/presensi/izin" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Izin</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div style="padding-top: 75px; padding-bottom: 30px;">
    <div class="row">
        <div class="col">
            <form method="POST" action="/presensi/storeizin" id="frmIzin">
                @csrf
                <div class="form-group">
                    <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" placeholder="Tanggal">
                </div>
                <div class="form-group">
                    <select name="status" id="status" class="form-control selectmaterialize">
                        <option value="">Izin / Sakit</option>
                        <option value="i">Izin</option>
                        <option value="s">Sakit</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

    $(document).ready(function() {
        $(".datepicker").datepicker({

            format: "yyyy-mm-dd"
        });

        $("#tgl_izin").change(function(e) {
            var tgl_izin = $(this).val();
            $.ajax({
                type: 'POST'
                , url: '/presensi/cekpengajuanizin'
                , data: {
                    _token: "{{ csrf_token() }}"
                    , tgl_izin: tgl_izin
                }
                , cache: false
                , success: function(respond) {
                    if (respond == 1) {
                        Swal.fire({
                            title: 'Oops !'
                            , text: 'Anda Sudah Melakukan Input Pengjuan Izin Pada Tanggal Tersebut !'
                            , icon: 'warning'
                        }).then((result) => {
                            $("#tgl_izin").val("");
                        });
                    }
                }
            });
        });

        $("#frmIzin").submit(function() {
            var tgl_izin = $("#tgl_izin").val();
            var status = $("#status").val();
            var keterangan = $("#keterangan").val();
            if (tgl_izin == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Tanggal Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (status == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Status Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Ketereangan Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            }
        });
    });

</script>
@endpush
