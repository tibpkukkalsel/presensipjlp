@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Penerimaan</div>
        <div class="right"></div>
    </div>

    <style>
        .historicontent {
            display: flex;
            gap: 1px;
            margin-top: 15px;
        }

        .datapresensi {
            margin-left: 10px;
        }

        .status {
            position: absolute;
            right: 20px;
        }

        .card {
            border: 1px solid rgb(0, 136, 255);
        }
    </style>
    <!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top:70px">
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
        </div>
    </div>
    <div class="row" style="z-index: 0">
        <div class="col">
            <form method="GET" action="/presensi/penerimaan">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <select name="bulan" id="bulan" class="form-control selectmaterialize">
                                <option value="">Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option {{ Request('bulan') == $i ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $namabulan[$i] }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control selectmaterialize">
                                <option value="">Tahun</option>
                                @php
                                    $tahun_awal = 2022;
                                    $tahun_sekarang = date('Y');
                                    for ($t = $tahun_awal; $t <= $tahun_sekarang; $t++) {
                                        if (Request('tahun') == $t) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        echo "<option $selected value='$t'>$t</option>";
                                } @endphp
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary w-100">Cari Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row" style="position: fixed; width:100%; margin:auto; overflow-y:scroll; height:430px">
        <div class="col">
            @foreach ($datapenerimaan as $d)
                @php
                    if ($d->status == 'ada') {
                        $status = 'Penerimaan';
                    } else {
                        $status = 'Not Found';
                    }
                @endphp
                <div class="card mt-1 card_penerimaan" kode_penerimaan="{{ $d->kode_penerimaan }}" status_approved="{{ $d->status_approved }}"
                    data-toggle="modal" data-target="#actionSheetIconed">
                    <div class="card-body">
                        <div class="historicontent">
                            <div class="iconpresensi">
                                @if ($d->status == 'ada')
                                    <ion-icon name="document-text-outline"
                                        style="font-size: 48px; color:rgb(21, 95, 207)"></ion-icon>
                                @endif
                            </div>
                            <div class="datapresensi">
                                <h3 style="line-height: 20px">{{ date('d-m-Y', strtotime($d->tgl_penerimaan)) }}

                                </h3>
                                <h3>
                                Lokasi {{ (($d->nama_cabang)) }}
                                </h3>
                                <small>
                                    Jumlah Notice Keluar {{$d->notice_keluar}} Lembar<br>
                                    Lokal: Rp. <?php echo number_format($d->r2),0,',','-'; ?> <br>
                                    Online: Rp. <?php echo number_format($d->r4),0,',','-'; ?>
                </small>
                                <p>
                                    <br>
                                    @if ($d->status == 'ada')
                                        <span class="badge bg-primary"> Jumlah Penerimaan: Rp. <?php echo number_format($d->jumlah),0,',','-'; ?></span>
                                    @endif
                                    @if (!empty($d->doc_sid))
                                        <span style="color: blue">
                                            <ion-icon name="document-attach-outline"></ion-icon> Lihat SID
                                        </span>
                                    @endif
                                </p>

                            </div>


                            <div class="status">
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($d->status_approved == '1')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($d->status_approved == '2')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            <b>{{ date("d-m-Y",strtotime($d->tgl_penerimaan)) }} ({{ $d->status== "ada" ? "Sakit" : "Izin" }})</b><br>
        <small class="text-muted">{{ $d->jumlah }}</small>
    </div>
    @if ($d->status_approved == 0)
    <span class="badge bg-warning">Waiting</span>
    @elseif($d->status_approved==1)
    <span class="badge bg-success">Approved</span>
    @elseif($d->status_approved==2)
    <span class="badge bg-danger">Decline</span>
    @endif
</div>
</div>
</li>
</ul> --}}
            @endforeach
        </div>
    </div>
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">
        <a href="/penerimaan" class="fab bg-primary">
            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
        </a>
    </div>

    <div class="modal fade action-sheet" id="actionSheetIconed" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aksi</h5>
                </div>
                <div class="modal-body" id="penerimaanact">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade dialogbox" id="deleteConfirm" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin Dihapus ?</h5>
                </div>
                <div class="modal-body">
                    Data Pengajuan Izin Akan dihapus
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-secondary" data-dismiss="modal">Batalkan</a>
                        <a href="" class="btn btn-text-primary" id="hapuspenerimaan">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $(".card_penerimaan").click(function(e) {
                var kode_penerimaan = $(this).attr("kode_penerimaan");
                var status_approved = $(this).attr("status_approved");

                if (status_approved == 1) {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Data Sudah Disetujui, Tidak Dapat di Ubah !',
                        icon: 'warning'
                    })
                } else {
                    $("#penerimaanact").load('/penerimaan/' + kode_penerimaan + '/penerimaanact');
                }
            });
        });
    </script>

@endpush
