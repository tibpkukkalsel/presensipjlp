@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->

                <h2 class="page-title">
                    Set Jam Kerja Kantor / Cabang
                </h2>
            </div>

        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <form action="/konfigurasi/jamkerjadept/store" method="POST">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group mb-3">
                        <select name="kode_cabang" id="kode_cabang" class="form-select" required>
                            <option value="">Pilih Kantor / Cabang</option>
                            @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}">{{ strtoupper($d->nama_cabang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-lg-5 col-12 mb-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">
                                        Senin
                                        <input type="hidden" name="hari[]" value="Senin">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select" required>
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Selasa
                                        <input type="hidden" name="hari[]" value="Selasa">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Rabu
                                        <input type="hidden" name="hari[]" value="Rabu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Kamis
                                        <input type="hidden" name="hari[]" value="Kamis">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jumat
                                        <input type="hidden" name="hari[]" value="Jumat">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Sabtu
                                        <input type="hidden" name="hari[]" value="Sabtu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Simpan</button>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center bg-light">Master Jam Kerja</th>
                                </tr>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Awal Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Akhir Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jamkerja as $d)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">{{ $d->kode_jam_kerja }}</span></td>
                                    <td class="fw-semibold">{{ $d->nama_jam_kerja }}</td>
                                    <td class="font-monospace">{{ substr($d->awal_jam_masuk, 0, 5) }}</td>
                                    <td class="font-monospace fw-bold text-primary">{{ substr($d->jam_masuk, 0, 5) }}</td>
                                    <td class="font-monospace">{{ substr($d->akhir_jam_masuk, 0, 5) }}</td>
                                    <td class="font-monospace fw-bold text-success">{{ substr($d->jam_pulang, 0, 5) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
