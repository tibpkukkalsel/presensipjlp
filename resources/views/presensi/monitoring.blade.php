@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Monitoring Presensi
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
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-icon mb-3">
                                                <span class="input-icon-addon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-calendar-event" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                        </path>
                                                        <path d="M16 3l0 4"></path>
                                                        <path d="M8 3l0 4"></path>
                                                        <path d="M4 11l16 0"></path>
                                                        <path d="M8 15h2v2h-2z"></path>
                                                    </svg>
                                                </span>
                                                <input type="text" id="tanggal" value="{{ date('Y-m-d') }}" name="tanggal"
                                                    class="form-control" placeholder="Tanggal Presensi" autocomplete="off">
                                            </div>
                                        </div>
                                        @role('administrator', 'user')
                                            <div class="col-4">
                                                <div class="form-group mb-3">
                                                    <select name="kode_cabang" id="kode_cabang" class="form-select">
                                                        <option value="">Semua Cabang</option>
                                                        @foreach ($cabang as $c)
                                                            <option value="{{ $c->kode_cabang }}">{{ strtoupper($c->nama_cabang) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group mb-3">
                                                    <select name="kode_dept" id="kode_dept" class="form-select">
                                                        <option value="">Seksi</option>
                                                        @foreach ($departemen as $d)
                                                            <option value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px;" class="text-center">No</th>
                                                    <th style="width: 100px;">ID</th>
                                                    <th>Nama Pegawai</th>
                                                    <th>Seksi</th>
                                                    <th>Kantor / Cabang</th>
                                                    <th>Jadwal</th>
                                                    <th style="width: 100px;" class="text-center">Jam Masuk</th>
                                                    <th style="width: 70px;" class="text-center">Foto Masuk</th>
                                                    <th style="width: 100px;" class="text-center">Jam Pulang</th>
                                                    <th style="width: 70px;" class="text-center">Foto Pulang</th>
                                                    <th style="width: 100px;" class="text-center">Status</th>
                                                    <th style="width: 100px;" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="loadpresensi"></tbody>
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

    <div class="modal modal-blur fade" id="modal-tampilkanpeta" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi Presensi Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadmap">

                </div>

            </div>
        </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $("#tanggal").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            function loadpresensi() {
                var tanggal = $("#tanggal").val();
                var kode_cabang = $("#kode_cabang").val();
                var kode_dept = $("#kode_dept").val();
                $("#loadpresensi").html('<tr><td colspan="12" class="text-center py-3 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div> Sedang memuat data presensi...</td></tr>');
                $.ajax({
                    type: 'POST',
                    url: '/panel-adm14/getpresensi',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal,
                        kode_cabang: kode_cabang,
                        kode_dept: kode_dept
                    },
                    cache: false,
                    success: function(respond) {
                        $("#loadpresensi").html(respond);
                    },
                    error: function(xhr, status, error) {
                        $("#loadpresensi").html('<tr><td colspan="12" class="text-center text-danger py-3">Gagal memuat data presensi (' + (xhr.status || '') + ' ' + (xhr.statusText || error) + '). Silakan coba lagi.</td></tr>');
                    }
                });
            }
            $("#tanggal").change(function(e) {
                loadpresensi();
            });

            $("#kode_cabang").change(function(e) {
                loadpresensi();
            });

            $("#kode_dept").change(function(e) {
                loadpresensi();
            });

            loadpresensi();

        });
    </script>
@endpush
