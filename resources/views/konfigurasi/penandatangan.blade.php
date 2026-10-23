@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Konfigurasi Penandatangan Laporan
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        @if (Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::get('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ Session::get('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="/panel-adm14/konfigurasi/updatepenandatangan" method="POST">
            @csrf

            <!-- Baris Form Pejabat 1 & Pejabat 2 -->
            <div class="row">
                <!-- Pejabat 1 (Kiri / Mengetahui) -->
                <div class="col-md-6 mb-3">
                    <div class="card card-primary card-outline h-100">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">
                                Pejabat 1 (Mengetahui / Kiri)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="pejabat1_jabatan">Jabatan</label>
                                <input type="text" class="form-control" name="pejabat1_jabatan" id="pejabat1_jabatan"
                                    value="{{ old('pejabat1_jabatan', $konfigurasi->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL') }}"
                                    placeholder="Contoh: KEPALA BPKUK PROV. KALSEL" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="pejabat1_nama">Nama Lengkap &amp; Gelar</label>
                                <input type="text" class="form-control" name="pejabat1_nama" id="pejabat1_nama"
                                    value="{{ old('pejabat1_nama', $konfigurasi->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM') }}"
                                    placeholder="Contoh: FEBRY RAHARJO, S.IP, MM" required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label fw-bold" for="pejabat1_nip">NIP</label>
                                <input type="text" class="form-control" name="pejabat1_nip" id="pejabat1_nip"
                                    value="{{ old('pejabat1_nip', $konfigurasi->pejabat1_nip ?? '19870222 200701 1 002') }}"
                                    placeholder="Contoh: 19870222 200701 1 002" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pejabat 2 (Kanan / Kasubbag Tata Usaha) -->
                <div class="col-md-6 mb-3">
                    <div class="card card-success card-outline h-100">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">
                                Pejabat 2 (Kanan)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="pejabat2_jabatan">Jabatan</label>
                                <input type="text" class="form-control" name="pejabat2_jabatan" id="pejabat2_jabatan"
                                    value="{{ old('pejabat2_jabatan', $konfigurasi->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA') }}"
                                    placeholder="Contoh: KASUBBAG TATA USAHA" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="pejabat2_nama">Nama Lengkap &amp; Gelar</label>
                                <input type="text" class="form-control" name="pejabat2_nama" id="pejabat2_nama"
                                    value="{{ old('pejabat2_nama', $konfigurasi->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST') }}"
                                    placeholder="Contoh: ADIMAS FITRIANDI, ST" required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label fw-bold" for="pejabat2_nip">NIP</label>
                                <input type="text" class="form-control" name="pejabat2_nip" id="pejabat2_nip"
                                    value="{{ old('pejabat2_nip', $konfigurasi->pejabat2_nip ?? '19800813 200712 1 001') }}"
                                    placeholder="Contoh: 19800813 200712 1 001" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris Tempat Penandatanganan & Tombol Simpan -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card card-secondary card-outline">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="form-group">
                                        <label class="form-label fw-bold" for="kota_cetak">Kota / Tempat Penandatanganan</label>
                                        <input type="text" class="form-control" name="kota_cetak" id="kota_cetak"
                                            value="{{ old('kota_cetak', $konfigurasi->kota_cetak ?? 'Pelaihari') }}"
                                            placeholder="Contoh: Pelaihari" required>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris Pratinjau Tanda Tangan -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">
                                Pratinjau Tanda Tangan Laporan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless text-center" style="max-width: 700px; margin: 0 auto; font-family: Arial, sans-serif;">
                                    <tr>
                                        <td style="width: 50%;"></td>
                                        <td style="width: 50%;">
                                            <span id="preview_kota">{{ $konfigurasi->kota_cetak ?? 'Pelaihari' }}</span>, {{ date('d-m-Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">Mengetahui,</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" id="preview_pejabat1_jabatan">
                                            {{ $konfigurasi->pejabat1_jabatan ?? 'KEPALA BPKUK PROV. KALSEL' }}
                                        </td>
                                        <td class="fw-bold" id="preview_pejabat2_jabatan">
                                            {{ $konfigurasi->pejabat2_jabatan ?? 'KASUBBAG TATA USAHA' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 60px;"></td>
                                        <td style="height: 60px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: bottom;">
                                            <b><u><span id="preview_pejabat1_nama">{{ $konfigurasi->pejabat1_nama ?? 'FEBRY RAHARJO, S.IP, MM' }}</span></u></b><br>
                                            <span>NIP. <span id="preview_pejabat1_nip">{{ $konfigurasi->pejabat1_nip ?? '19870222 200701 1 002' }}</span></span>
                                        </td>
                                        <td style="vertical-align: bottom;">
                                            <b><u><span id="preview_pejabat2_nama">{{ $konfigurasi->pejabat2_nama ?? 'ADIMAS FITRIANDI, ST' }}</span></u></b><br>
                                            <span>NIP. <span id="preview_pejabat2_nip">{{ $konfigurasi->pejabat2_nip ?? '19800813 200712 1 001' }}</span></span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        function syncPreview() {
            var p1_jab = $('#pejabat1_jabatan').val().trim();
            var p1_nama = $('#pejabat1_nama').val().trim();
            var p1_nip = $('#pejabat1_nip').val().trim();

            var p2_jab = $('#pejabat2_jabatan').val().trim();
            var p2_nama = $('#pejabat2_nama').val().trim();
            var p2_nip = $('#pejabat2_nip').val().trim();

            var kota = $('#kota_cetak').val().trim();

            $('#preview_pejabat1_jabatan').text(p1_jab || '-');
            $('#preview_pejabat1_nama').text(p1_nama || '-');
            $('#preview_pejabat1_nip').text(p1_nip || '-');

            $('#preview_pejabat2_jabatan').text(p2_jab || '-');
            $('#preview_pejabat2_nama').text(p2_nama || '-');
            $('#preview_pejabat2_nip').text(p2_nip || '-');

            $('#preview_kota').text(kota || 'Pelaihari');
        }

        $('#pejabat1_jabatan, #pejabat1_nama, #pejabat1_nip, #pejabat2_jabatan, #pejabat2_nama, #pejabat2_nip, #kota_cetak').on('input propertychange paste keyup change', syncPreview);
    });
</script>
@endpush
