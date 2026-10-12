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

    #keterangan {
        height: 5rem !important;
    }

</style>
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Penerimaan</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <form method="POST" action="/penerimaan/{{ $datapenerimaan->kode_penerimaan }}/update" id="frmIzin">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_penerimaan" value="{{ $datapenerimaan->tgl_penerimaan }}" autocomplete="off" name="tgl_penerimaan" class="form-control datepicker" placeholder="Tanggal Penerimaan">
            </div>
            <div class="form-group">
                <input type="text" id="r2" value="{{ $datapenerimaan->r2 }}" autocomplete="off" name="r2" class="form-control" placeholder="Penerimaan Lokal">
            </div>
            <div class="form-group">
                <input type="text" id="r4" value="{{ $datapenerimaan->r4 }}" autocomplete="off" name="r4" class="form-control" placeholder="Penerimaan Online">
            </div>
        <!--<div class="form-group">
                <input type="text" id="jumlah" name="jumlah" class="form-control" autocomplete="off" placeholder="Jumlah penerimaan" readonly>
            </div> -->
            <div class="form-group">
                <input type="text" id="notice_keluar" value="{{ $datapenerimaan->notice_keluar }}" name="notice_keluar" class="form-control" autocomplete="off" placeholder="Notice Keluar">
            </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Kirim</button>
            </div>
        </form>
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


        // $("#tgl_izin").change(function(e) {
        //     var tgl_izin = $(this).val();
        //     $.ajax({
        //         type: 'POST'
        //         , url: '/presensi/cekpengajuanizin'
        //         , data: {
        //             _token: "{{ csrf_token() }}"
        //             , tgl_izin: tgl_izin
        //         }
        //         , cache: false
        //         , success: function(respond) {
        //             if (respond == 1) {
        //                 Swal.fire({
        //                     title: 'Oops !'
        //                     , text: 'Anda Sudah Melakukan Input Pengjuan Izin Pada Tanggal Tersebut !'
        //                     , icon: 'warning'
        //                 }).then((result) => {
        //                     $("#tgl_izin").val("");
        //                 });
        //             }
        //         }
        //     });
        // });

        $("#frmIzin").submit(function() {
            var tgl_izin_dari = $("#tgl_izin_dari").val();
            var tgl_izin_sampai = $("#tgl_izin_sampai").val();
            var keterangan = $("#keterangan").val();
            if (tgl_izin_dari == "" || tgl_izin_sampai == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Tanggal Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops !'
                    , text: 'Keterangan Harus Diisi'
                    , icon: 'warning'
                });
                return false;
            }
        });
    });

</script>
@endpush
