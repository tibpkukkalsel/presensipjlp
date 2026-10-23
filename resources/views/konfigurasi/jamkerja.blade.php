@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->

                    <h2 class="page-title">
                        Konfigurasi Jam Kerja
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
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahJK">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>
                                        Tambah Data
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 50px;">No</th>
                                                    <th class="text-center" style="width: 90px;">Kode JK</th>
                                                    <th>Nama Jam Kerja</th>
                                                    <th class="text-center">Awal Masuk</th>
                                                    <th class="text-center">Jam Masuk</th>
                                                    <th class="text-center">Akhir Masuk</th>
                                                    <th class="text-center">Jam Pulang</th>
                                                    <th class="text-center" style="width: 90px;">Total Jam</th>
                                                    <th class="text-center" style="width: 100px;">Lintas Hari</th>
                                                    <th class="text-center" style="width: 90px;">Istirahat</th>
                                                    <th class="text-center">Jam Istirahat</th>
                                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jam_kerja as $d)
                                                    <tr>
                                                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                                        <td class="text-center"><span class="badge bg-light text-dark font-monospace border">{{ $d->kode_jam_kerja }}</span></td>
                                                        <td class="fw-semibold">{{ $d->nama_jam_kerja }}</td>
                                                        <td class="text-center font-monospace">{{ substr($d->awal_jam_masuk, 0, 5) }}</td>
                                                        <td class="text-center font-monospace fw-bold text-primary">{{ substr($d->jam_masuk, 0, 5) }}</td>
                                                        <td class="text-center font-monospace">{{ substr($d->akhir_jam_masuk, 0, 5) }}</td>
                                                        <td class="text-center font-monospace fw-bold text-success">{{ substr($d->jam_pulang, 0, 5) }}</td>
                                                        <td class="text-center"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $d->total_jam }} Jam</span></td>
                                                        <td class="text-center">
                                                            @if ($d->lintashari == 1)
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Ya</span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Tidak</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($d->status_istirahat == 1)
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Ya</span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Tidak</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($d->status_istirahat == 1 && $d->awal_jam_istirahat && $d->akhir_jam_istirahat)
                                                                <span class="badge bg-light text-dark font-monospace border">{{ substr($d->awal_jam_istirahat, 0, 5) }} - {{ substr($d->akhir_jam_istirahat, 0, 5) }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                                <a href="javascript:void(0)" class="edit btn btn-info btn-sm btn-action"
                                                                    kode_jam_kerja="{{ $d->kode_jam_kerja }}" title="Edit Jam Kerja">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon icon-tabler icon-tabler-edit"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                                                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                                                        <path d="M16 5l3 3"></path>
                                                                    </svg>
                                                                </a>
                                                                <form action="/panel-adm14/konfigurasi/jamkerja/{{ $d->kode_jam_kerja }}/delete"
                                                                    method="POST" class="m-0 d-inline-block">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm btn-action delete-confirm" title="Hapus Jam Kerja">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="icon icon-tabler icon-tabler-trash-filled"
                                                                            width="24" height="24" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor"
                                                                            fill="none" stroke-linecap="round"
                                                                            stroke-linejoin="round">
                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                            <path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" stroke-width="0" fill="currentColor"></path>
                                                                            <path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" stroke-width="0" fill="currentColor"></path>
                                                                        </svg>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-inputjk" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Jam Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/panel-adm14/konfigurasi/storejamkerja" method="POST" id="frmJK">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-barcode" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7v-1a2 2 0 0 1 2 -2h2"></path>
                                            <path d="M4 17v1a2 2 0 0 0 2 2h2"></path>
                                            <path d="M16 4h2a2 2 0 0 1 2 2v1"></path>
                                            <path d="M16 20h2a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M5 11h1v2h-1z"></path>
                                            <path d="M10 11l0 2"></path>
                                            <path d="M14 11h1v2h-1z"></path>
                                            <path d="M19 11l0 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="kode_jam_kerja" class="form-control"
                                        placeholder="Kode Jam Kerja" name="kode_jam_kerja">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-barcode" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M4 7v-1a2 2 0 0 1 2 -2h2"></path>
                                            <path d="M4 17v1a2 2 0 0 0 2 2h2"></path>
                                            <path d="M16 4h2a2 2 0 0 1 2 2v1"></path>
                                            <path d="M16 20h2a2 2 0 0 0 2 -2v-1"></path>
                                            <path d="M5 11h1v2h-1z"></path>
                                            <path d="M10 11l0 2"></path>
                                            <path d="M14 11h1v2h-1z"></path>
                                            <path d="M19 11l0 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="nama_jam_kerja" class="form-control"
                                        placeholder="Nama Jam Kerja" name="nama_jam_kerja">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="awal_jam_masuk" class="form-control"
                                        placeholder="Awal Jam Masuk" name="awal_jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="jam_masuk" class="form-control"
                                        placeholder="Jam Masuk" name="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="akhir_jam_masuk" class="form-control"
                                        placeholder="Akhir Jam Masuk" name="akhir_jam_masuk">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="jam_pulang" class="form-control"
                                        placeholder="Jam Pulang" name="jam_pulang">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="total_jam" class="form-control"
                                        placeholder="Total Jam" name="total_jam">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status_istirahat" id="status_istirahat" class="form-select">
                                        <option value="">Istirahat</option>
                                        <option value="1">Ada</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row setjamistirahat">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="awal_jam_istirahat" class="form-control"
                                        placeholder="Awal Jam Istirahat" name="awal_jam_istirahat">
                                </div>
                            </div>
                        </div>
                        <div class="row setjamistirahat">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M12 10l0 3l2 0"></path>
                                            <path d="M7 4l-2.75 2"></path>
                                            <path d="M17 4l2.75 2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" id="akhir_jam_istirahat" class="form-control"
                                        placeholder="Akhir Jam Istirahat" name="akhir_jam_istirahat">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="lintashari" id="lintashari" class="form-select">
                                        <option value="">Lintas Hari</option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 14l11 -11"></path>
                                            <path
                                                d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5">
                                            </path>
                                        </svg>
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-editjk" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Jam Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadeditform">

                </div>

            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {

            function showsetjamistirahat() {
                var status_istirahat = $("#status_istirahat").val();
                if (status_istirahat == "1") {
                    $(".setjamistirahat").show();
                } else {
                    $(".setjamistirahat").hide();
                }
            }

            $("#status_istirahat").change(function() {
                showsetjamistirahat();
            });
            showsetjamistirahat();

            $("#awal_jam_masuk, #jam_masuk, #akhir_jam_masuk, #jam_pulang,#awal_jam_istirahat,#akhir_jam_istirahat")
                .mask("00:00");

            $("#btnTambahJK").click(function(e) {
                e.preventDefault();
                var modalEl = document.getElementById('modal-inputjk');
                if (window.bootstrap && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                } else {
                    $("#modal-inputjk").modal("show");
                }
            });

            $(".delete-confirm").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin Data Ini Mau Di Hapus ?',
                    text: "Jika Ya Maka Data Akan Terhapus Permanent",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus Saja!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Deleted!', 'Data Berhasil Di Hapus', 'success'
                        )
                    }
                })
            });

            $("#frmJK").submit(function() {
                var kode_jam_kerja = $("#kode_jam_kerja").val();
                var nama_jam_kerja = $("#nama_jam_kerja").val();
                var awal_jam_masuk = $("#awal_jam_masuk").val();
                var jam_masuk = $("#jam_masuk").val();
                var akhir_jam_masuk = $("#akhir_jam_masuk").val();
                var awal_jam_istirahat = $("#awal_jam_istirahat").val();
                var akhir_jam_istirahat = $("#akhir_jam_istirahat").val();
                var jam_pulang = $("#jam_pulang").val();
                var total_jam = $("#total_jam").val();
                var lintashari = $("#lintashari").val();
                var status_istirahat = $("#status_istirahat").val();
                if (kode_jam_kerja == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Kode Jam Kerja Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#kode_jam_kerja").focus();
                    });

                    return false;
                } else if (nama_jam_kerja == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Jam Kerja Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#nama_jam_kerja").focus();
                    });

                    return false;
                } else if (awal_jam_masuk == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Awal Jam Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#awal_jam_masuk").focus();
                    });

                    return false;
                } else if (jam_masuk == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#jam_masuk").focus();
                    });

                    return false;
                } else if (akhir_jam_masuk == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Akhir Masuk Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#akhir_jam_masuk").focus();
                    });

                    return false;
                } else if (status_istirahat === "") {

                    Swal.fire({
                        title: 'Warning!',
                        text: 'Status Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#status_istirahat").focus();
                    });

                    return false;
                } else if (awal_jam_istirahat == "" && status_istirahat == "1") {

                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Awal Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#awal_jam_istirahat").focus();
                    });

                    return false;
                } else if (akhir_jam_istirahat == "" && status_istirahat == "1") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Akhir Istirahat Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#akhir_jam_istirahat").focus();
                    });

                    return false;
                } else if (jam_pulang == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jam Pulang Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#jam_pulang").focus();
                    });

                    return false;
                } else if (total_jam == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Total Jam Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#total_jam").focus();
                    });

                    return false;
                } else if (lintashari == "") {
                    // alert('Nik Harus Diisi');
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Lintas Hari Harus Diisi !',
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $("#lintashari").focus();
                    });

                    return false;
                }
            });


            $(".edit").click(function(e) {
                e.preventDefault();
                var kode_jam_kerja = $(this).attr('kode_jam_kerja');

                // Tampilkan modal edit dan tampilkan indikator loading
                var modalEl = document.getElementById('modal-editjk');
                if (window.bootstrap && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                } else {
                    $("#modal-editjk").modal("show");
                }

                $("#loadeditform").html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><div class="mt-2 text-muted small">Memuat data jam kerja...</div></div>');

                $.ajax({
                    type: 'POST',
                    url: '/panel-adm14/konfigurasi/editjamkerja',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_jam_kerja: kode_jam_kerja
                    },
                    success: function(respond) {
                        $("#loadeditform").html(respond);
                    },
                    error: function(xhr, status, error) {
                        $("#loadeditform").html('<div class="alert alert-danger mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal memuat data jam kerja (' + xhr.status + ': ' + (xhr.statusText || error) + '). Silakan coba lagi.</div>');
                    }
                });
            });
        });
    </script>
@endpush
