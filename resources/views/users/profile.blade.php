@extends('layouts.admin.tabler')

@section('content')
    <style>
        .profile-user-img {
            width: 110px;
            height: 110px;
            object-fit: contain;
            background-color: #ffffff;
            border: 3px solid #dee2e6;
            padding: 6px;
            margin: 0 auto;
        }
    </style>

    <!-- Content Header (Page header) -->
    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <h3 class="m-0 fw-bold text-dark">
                Profil Superadmin
            </h3>
            <small class="text-muted">Kelola data profil dan kata sandi akun superadmin</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end mb-0">
                <li class="breadcrumb-item"><a href="/panel-adm14/dashboardadmin"><i class="bi bi-house-door me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="row">
        <div class="col-12">
            @if (Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::get('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ Session::get('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Terjadi kesalahan validasi:
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Widget Profil Khas AdminLTE -->
        <div class="col-lg-4 col-md-5 mb-3">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile text-center py-4">
                    <div class="text-center mb-3">
                        <img class="profile-user-img rounded-circle shadow-sm"
                            src="{{ asset('assets/img/Logo Provinsi Kalimantan Selatan.png') }}"
                            alt="Logo Provinsi Kalimantan Selatan" onerror="this.src='{{ asset('Logo Presis.png') }}'">
                    </div>

                    <h4 class="profile-username text-center fw-bold text-dark mb-1">{{ $user->name }}</h4>
                    <p class="text-muted text-center mb-2">{{ $user->email }}</p>

                    <div class="text-center mt-2">
                        <span class="badge text-bg-primary px-3 py-2 rounded-pill">
                            {{ ucwords($user->role_name ?? 'Super Admin') }}
                        </span>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <!-- Kolom Kanan: Form Edit Data Diri & Kata Sandi -->
        <div class="col-lg-8 col-md-7 mb-3">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold m-0 text-dark">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Diri &amp; Kata Sandi
                    </h5>
                </div>
                <!-- /.card-header -->
                <form action="/panel-adm14/updateprofile" method="POST" autocomplete="off">
                    @csrf
                    <div class="card-body p-4">
                        <!-- Section: Data Diri -->
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                            Informasi Dasar
                        </h6>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    Alamat Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="Masukkan alamat email" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section: Kata Sandi -->
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-2 mt-2">
                            Keamanan &amp; Kata Sandi
                        </h6>
                        <div class="alert alert-info py-2 px-3 small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan kolom kata sandi jika Anda tidak ingin mengubah kata sandi saat ini.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Minimal 6 karakter"
                                        autocomplete="new-password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="#password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi
                                    Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Ulangi kata sandi baru"
                                        autocomplete="new-password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="#password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <!-- Section: Tombol Aksi (Card Footer khas AdminLTE) -->
                    <div class="card-footer bg-light py-3 d-flex justify-content-end gap-2">
                        <a href="/panel-adm14/dashboardadmin" class="btn btn-light border px-4">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $('.toggle-password').click(function() {
                var targetInput = $($(this).data('target'));
                var icon = $(this).find('i');
                if (targetInput.attr('type') === 'password') {
                    targetInput.attr('type', 'text');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    targetInput.attr('type', 'password');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        });
    </script>
@endpush
