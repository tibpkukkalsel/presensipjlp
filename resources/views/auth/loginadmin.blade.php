<!doctype html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#334155">
    <title>PRESIS - Admin Panel</title>
    <meta name="description" content="Panel Administrator E-Presensi dan Laporan BPKUK Prov. Kalsel">
    <link rel="icon" type="image/png" href="{{ asset('Logo Presis.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Logo Presis.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom Admin Login Stylesheet (Gray / Slate Theme) -->
    <link rel="stylesheet" href="{{ asset('assets/css/loginadmin.css') }}?v={{ time() }}">
</head>

<body>

    <div class="page-container">

        <!-- ================= DESKTOP LEFT PANEL (Curved Slate / Charcoal Wave) ================= -->
        <div class="panel-side">
            <!-- SVG Wave Overlay creating smooth organic curve into white area -->
            <svg class="desktop-wave" viewBox="0 0 100 500" preserveAspectRatio="none" fill="#FFFFFF">
                <path d="M 100 0 L 100 500 L 0 500 C 70 380, 85 240, 15 110 C -5 70, 0 30, 20 0 Z" />
            </svg>

            <!-- Text Content -->
            <div class="side-content">
                <h2 class="side-title">PRESIS ADMIN</h2>
                <p class="side-desc">
                    Selamat datang di Panel Administrasi Sistem Presensi &amp; Pelaporan Pegawai BPKUK Prov. Kalsel.
                    Silakan masuk untuk mengelola data dan sistem.
                </p>
            </div>

            <!-- Pegawai Presensi & PJLP PDH Illustration -->
            <div class="side-illustration">
                <img src="{{ asset('assets/img/login_vector_admin.png') }}" alt="Ilustrasi Pegawai PRESIS BPKUK"
                    class="img-illustration">
            </div>
        </div>

        <!-- ================= FORM PANEL (White Side) ================= -->
        <div class="panel-form">
            <div class="form-wrap">

                <div class="form-header">
                    <img src="{{ asset('assets/img/Logo Provinsi Kalimantan Selatan.png') }}"
                        alt="Logo Provinsi Kalimantan Selatan" class="form-logo">
                    <h1 class="form-title">Sign in</h1>
                </div>

                <!-- Warning Alert -->
                @php
                    $messagewarning = Session::get('warning');
                @endphp
                @if (Session::get('warning'))
                    <div class="alert-warning">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>{{ $messagewarning }}</span>
                    </div>
                @endif

                <form action="/panel-adm14/prosesloginadmin" method="POST" id="formLoginAdmin" autocomplete="off">
                    @csrf

                    <!-- Input 1: Username / Email Admin -->
                    <div class="input-pill">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" class="input-field"
                            placeholder="Username / Email Admin" required autocomplete="username" autofocus>
                    </div>

                    <!-- Input 2: Password -->
                    <div class="input-pill">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                </rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" class="input-field"
                            placeholder="Password" required autocomplete="current-password">
                        <button type="button" class="btn-eye" id="togglePasswordBtn" aria-label="Toggle password">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>

                    <!-- Options: Remember me & Forgot Password -->
                    <div class="options-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Ingat saya</span>
                        </label>
                        <a href="javascript:void(0)" class="forgot-link"
                            onclick="alert('Silahkan hubungi Superadmin BPKUK Prov. Kalsel untuk reset akun administrator.')">Forgot
                            Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        MASUK PANEL
                    </button>

                </form>

                <div class="form-footer">
                    Balai Pelatihan Koperasi dan Usaha Kecil <br> Provinsi Kalimantan Selatan
                </div>

            </div>
        </div>

        <!-- ================= MOBILE BOTTOM PANEL (Curved Wave & Illustration) ================= -->
        <div class="mobile-bottom-panel">
            <!-- Smooth Curved Wave on Top of Slate Section -->
            <div class="mobile-wave-top">
                <svg viewBox="0 0 500 70" preserveAspectRatio="none" fill="#FFFFFF">
                    <path d="M 0 0 L 500 0 C 360 65, 140 65, 0 0 Z" />
                </svg>
            </div>

            <div class="mobile-info-wrap">
                <h3 class="mobile-info-title">PRESIS ADMIN</h3>
                <p class="mobile-info-desc">
                    Panel Kontrol &amp; Pengelolaan Presensi Online BPKUK Prov. Kalsel
                </p>

                <!-- Mini Illustration for Mobile -->
                <div class="mobile-illustration">
                    <img src="{{ asset('assets/img/login_vector_admin.png') }}" alt="Ilustrasi Pegawai PRESIS BPKUK"
                        class="img-illustration">
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        // Password Visibility Toggle
        var toggleBtn = document.getElementById('togglePasswordBtn');
        var passwordInput = document.getElementById('password');
        var eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML =
                        '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML =
                        '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                }
            });
        }
    </script>
</body>

</html>
