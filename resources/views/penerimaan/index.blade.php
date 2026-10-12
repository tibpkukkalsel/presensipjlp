@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->

                    <h2 class="page-title">
                        Data Realisasi Penerimaan
                    </h2>
                </div>

            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-fluid">
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
                    <form action="/presensi/realisasi" method="GET" autocomplete="off">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
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
                                    <input type="text" value="{{ Request('dari') }}" id="dari" class="form-control"
                                        placeholder="Dari" name="dari">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
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
                                    <input type="text" value="{{ Request('sampai') }}" id="sampai"
                                        class="form-control" placeholder="Sampai" name="sampai">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        @role('administrator', 'user')
                            <div class="col-3">
                                <div class="input-icon mb-3">
                                    <select name="nik" id="nik" class="form-select">
                                            <option value="">Semua Mobil</option>
                                            @foreach ($karyawan as $d)
                                                <option {{ Request('nik') == $d->nik ? 'selected' : '' }}
                                                    value="{{ $d->nik }}">
                                                    {{ strtoupper($d->nama_lengkap) }}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>
                            @endrole

                            @role('administrator', 'user')
                                <div class="col-2">
                                    <div class="form-group">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $d)
                                                <option {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}
                                                    value="{{ $d->kode_cabang }}">
                                                    {{ strtoupper($d->nama_cabang) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            @endrole
                            <div class="col-2">
                                <div class="form-group">
                                    <select name="status_approved" id="status_approved" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="0" {{ Request('status_approved') === '0' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="1" {{ Request('status_approved') == 1 ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="2" {{ Request('status_approved') == 2 ? 'selected' : '' }}>
                                            Ditolak</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-1">
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-search" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                            <path d="M21 21l-6 -6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Penerimaan</th>
                                <th>Tanggal</th>
                                <th>Mobil</th>
                                <th>Lokasi</th>
                                <th>Lokal (Rp)</th>
                                <th>Online (Rp)</th>
                                <th>Jumlah (Rp)</th>
                                <th>Notice Keluar</th>
                                <th>Status Approve</th>
                                @role('administrator', 'user')
                                    <th>Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penerimaan as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->kode_penerimaan }}</td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($d->tgl_penerimaan)) }}
                                    </td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->nama_cabang }}</td>
                                    <td><?php echo number_format($d->r2),0,',','-'; ?></td>
                                    <td><?php echo number_format($d->r4),0,',','-'; ?></td>
                                    <td><?php echo number_format($d->jumlah),0,',','-'; ?></td>
                                    <td>{{ $d->notice_keluar }}</td>
                                    <td>
                                        @if ($d->status_approved == 1)
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($d->status_approved == 2)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    @role('administrator', 'user')
                                        <td>
                                            @if ($d->status_approved == 0)
                                                <a href="#" class="btn btn-sm btn-primary approve"
                                                    kode_penerimaan="{{ $d->kode_penerimaan }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-external-link" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5">
                                                        </path>
                                                        <path d="M10 14l10 -10"></path>
                                                        <path d="M15 4l5 0l0 5"></path>
                                                    </svg>
                                                </a>
                                            @else
                                                <a href="/presensi/{{ $d->kode_penerimaan }}/batalkanpenerimaan"
                                                    class="btn btn-sm bg-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="icon icon-tabler icon-tabler-square-rounded-x" width="24"
                                                        height="24" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M10 10l4 4m0 -4l-4 4"></path>
                                                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z">
                                                        </path>
                                                    </svg>
                                                    Batalkan
                                                </a>
                                            @endif

                                        </td>
                                    @endrole
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $penerimaan->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-izinsakit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Realisasi Penerimaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/presensi/approvepenerimaan" method="POST">
                        @csrf
                        <input type="hidden" id="kode_izin_form" name="kode_izin_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status_approved" id="status_approved" class="form-select">
                                        <option value="1">Disetujui</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12">
                                <div class="form-gropu">
                                    <button class="btn btn-primary w-100" type="submit">
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
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $(".approve").click(function(e) {
                e.preventDefault();
                var kode_penerimaan = $(this).attr("kode_penerimaan");
                $("#kode_izin_form").val(kode_penerimaan);
                $("#modal-izinsakit").modal("show");
            });

            $("#dari, #sampai").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endpush
