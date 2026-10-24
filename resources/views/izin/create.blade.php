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
    <div class="pageTitle">Form Izin Absen</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div style="padding-top: 75px; padding-bottom: 30px;">
    <div class="row">
        <div class="col">
            <form method="POST" action="/izinabsen/store" id="frmIzin">
                @csrf
                <div class="form-group">
                    <select name="status" id="status" class="form-control selectmaterialize">
                        <option value="i">Izin Absen (Tidak Masuk Kerja)</option>
                        <option value="it">Izin Terlambat (Masuk Kerja)</option>
                        <option value="ipc">Izin Pulang Cepat (Pulang Awal)</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_dari" autocomplete="off" name="tgl_izin_dari" class="form-control datepicker" placeholder="Dari Tanggal">
                </div>
                <div class="form-group" id="group_sampai">
                    <input type="text" id="tgl_izin_sampai" autocomplete="off" name="tgl_izin_sampai" class="form-control datepicker" placeholder="Sampai Tanggal">
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" autocomplete="off" placeholder="Jumlah Hari" readonly>
                </div>
                <div class="form-group">
                    <input type="text" id="keterangan" name="keterangan" class="form-control" autocomplete="off" placeholder="Keterangan / Alasan">
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

        function loadjumlahhari() {
            var dari = $("#tgl_izin_dari").val();
            var sampai = $("#tgl_izin_sampai").val();
            var date1 = new Date(dari);
            var date2 = new Date(sampai);

            // To calculate the time difference of two dates
            var Difference_In_Time = date2.getTime() - date1.getTime();

            // To calculate the no. of days between two dates
            var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

            if (dari == "" || sampai == "") {
                var jmlhari = 0;
            } else {
                var jmlhari = Difference_In_Days + 1;
            }

            //To display the final no. of days (result)
            $("#jml_hari").val(jmlhari + " Hari");
        }

        $("#status").change(function() {
            var val = $(this).val();
            if (val == 'it' || val == 'ipc') {
                $("#group_sampai").hide();
                if ($("#tgl_izin_dari").val() != "") {
                    $("#tgl_izin_sampai").val($("#tgl_izin_dari").val());
                }
                $("#jml_hari").val("1 Hari");
            } else {
                $("#group_sampai").show();
                loadjumlahhari();
            }
        });

        $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
            var val = $("#status").val();
            if (val == 'it' || val == 'ipc') {
                $("#tgl_izin_sampai").val($("#tgl_izin_dari").val());
                $("#jml_hari").val("1 Hari");
            } else {
                loadjumlahhari();
            }
        });

        $("#frmIzin").submit(function() {
            var val = $("#status").val();
            var tgl_izin_dari = $("#tgl_izin_dari").val();
            var tgl_izin_sampai = $("#tgl_izin_sampai").val();
            var keterangan = $("#keterangan").val();

            if (val == 'it' || val == 'ipc') {
                if (tgl_izin_sampai == "") {
                    $("#tgl_izin_sampai").val(tgl_izin_dari);
                    tgl_izin_sampai = tgl_izin_dari;
                }
            }

            if (tgl_izin_dari == "" || (val == 'i' && tgl_izin_sampai == "")) {
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
