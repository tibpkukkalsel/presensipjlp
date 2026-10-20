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
    <link rel="stylesheet" href="{{ asset('assets/css/loginadmin.css') }}">
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

            <!-- Admin Server & Monitoring Station Illustration -->
            <div class="side-illustration">
                <svg viewBox="0 0 340 225" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Ground Base Shadow -->
                    <ellipse cx="170" cy="214" rx="125" ry="9" fill="#0F172A"
                        opacity="0.45" />

                    <!-- Radar / Geofencing concentric waves (Graphite/Silver) -->
                    <circle cx="95" cy="115" r="50" stroke="rgba(241,245,249,0.18)" stroke-dasharray="4 4"
                        fill="none" />
                    <circle cx="95" cy="115" r="75" stroke="rgba(241,245,249,0.1)" stroke-dasharray="5 5"
                        fill="none" />

                    <!-- Floating Top Badge: Admin Security Status -->
                    <g transform="translate(100, 10)">
                        <rect width="138" height="24" rx="12" fill="#FFFFFF"
                            filter="drop-shadow(0 3px 8px rgba(0,0,0,0.25))" />
                        <!-- Security Shield Icon -->
                        <circle cx="14" cy="12" r="6" fill="#10B981" />
                        <path d="M11 12 L13 14 L17 10" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" />
                        <text x="26" y="16" fill="#1E293B" font-size="9" font-weight="700"
                            font-family="'Poppins', sans-serif">Sistem Terproteksi</text>
                    </g>

                    <!-- Left: Admin Monitoring Dashboard Monitor -->
                    <g transform="translate(32, 40)">
                        <!-- Monitor Shadow -->
                        <rect x="0" y="4" width="124" height="140" rx="16" fill="#0A0F1D" opacity="0.4" />

                        <!-- Outer Monitor Bezel (Dark Titanium) -->
                        <rect x="0" y="0" width="124" height="140" rx="16" fill="#1E293B" stroke="#64748B"
                            stroke-width="1.8" />

                        <!-- Screen Surface -->
                        <rect x="6" y="6" width="112" height="128" rx="12" fill="#0F172A" />

                        <!-- Top Title Bar -->
                        <rect x="6" y="6" width="112" height="20" rx="10" fill="#334155" />
                        <circle cx="16" cy="16" r="3" fill="#EF4444" />
                        <circle cx="24" cy="16" r="3" fill="#F59E0B" />
                        <circle cx="32" cy="16" r="3" fill="#10B981" />
                        <text x="70" y="19" fill="#F8FAFC" font-size="7.5" font-weight="600" text-anchor="middle"
                            font-family="'Poppins', sans-serif">PANEL MONITOR</text>

                        <!-- Dashboard Mini Cards inside Screen -->
                        <!-- Card 1: Stat Gauge -->
                        <rect x="12" y="32" width="48" height="34" rx="6" fill="#1E293B" />
                        <text x="18" y="44" fill="#94A3B8" font-size="6"
                            font-family="'Poppins', sans-serif">PEGAWAI</text>
                        <text x="18" y="58" fill="#38BDF8" font-size="11" font-weight="700"
                            font-family="'Poppins', sans-serif">100%</text>

                        <!-- Card 2: Status Indicator -->
                        <rect x="64" y="32" width="48" height="34" rx="6" fill="#1E293B" />
                        <text x="70" y="44" fill="#94A3B8" font-size="6"
                            font-family="'Poppins', sans-serif">SERVER</text>
                        <text x="70" y="58" fill="#10B981" font-size="10" font-weight="700"
                            font-family="'Poppins', sans-serif">AKTIF</text>

                        <!-- Chart Graphic Bars -->
                        <g transform="translate(14, 74)">
                            <rect width="96" height="52" rx="6" fill="#1E293B" />
                            <!-- Mini Graph Bars -->
                            <rect x="10" y="32" width="8" height="14" rx="2" fill="#475569" />
                            <rect x="22" y="22" width="8" height="24" rx="2" fill="#64748B" />
                            <rect x="34" y="16" width="8" height="30" rx="2" fill="#38BDF8" />
                            <rect x="46" y="26" width="8" height="20" rx="2" fill="#64748B" />
                            <rect x="58" y="12" width="8" height="34" rx="2" fill="#10B981" />
                            <rect x="70" y="18" width="8" height="28" rx="2" fill="#38BDF8" />
                            <rect x="82" y="28" width="8" height="18" rx="2" fill="#64748B" />
                        </g>

                        <!-- Monitor Stand -->
                        <rect x="54" y="140" width="16" height="16" fill="#475569" />
                        <path d="M42 156 L82 156 L86 162 L38 162 Z" fill="#334155" />
                    </g>

                    <!-- Right: Administrator Figure in Formal Charcoal Suit -->
                    <g transform="translate(178, 42)">
                        <!-- Head & Neck -->
                        <rect x="36" y="32" width="14" height="14" fill="#FCD34D" rx="2" />
                        <!-- Face -->
                        <ellipse cx="43" cy="22" rx="14" ry="16" fill="#FCD34D" />
                        <!-- Neat Executive Hair -->
                        <path d="M28 20 C28 5 58 5 58 20 C58 12 48 8 40 10 C33 12 30 16 28 20 Z" fill="#0F172A" />
                        <!-- Glasses / Executive look -->
                        <circle cx="37" cy="20" r="3.5" stroke="#0F172A" stroke-width="1.2"
                            fill="none" />
                        <circle cx="49" cy="20" r="3.5" stroke="#0F172A" stroke-width="1.2"
                            fill="none" />
                        <line x1="40.5" y1="20" x2="45.5" y2="20" stroke="#0F172A"
                            stroke-width="1.2" />

                        <!-- Jas Formal Charcoal (Dark Slate / Charcoal Suit) -->
                        <path d="M16 44 L70 44 L74 114 L12 114 Z" fill="#334155" />
                        <!-- Kemeja Putih di balik Jas -->
                        <polygon points="36,44 50,44 43,80" fill="#FFFFFF" />
                        <!-- Dasi Merah / Marun Formal -->
                        <polygon points="41,48 45,48 44,78 43,84 42,78" fill="#DC2626" />
                        <!-- Kerah Jas Formal -->
                        <polygon points="26,44 38,72 32,74 20,44" fill="#1E293B" />
                        <polygon points="60,44 48,72 54,74 66,44" fill="#1E293B" />

                        <!-- Pin Lencana Emas -->
                        <circle cx="58" cy="56" r="2.5" fill="#F59E0B" />

                        <!-- Tangan Memegang Tablet Admin -->
                        <path d="M16 46 L-2 76 L6 82 L22 56 Z" fill="#334155" />
                        <path d="M70 46 L86 78 L78 84 L64 56 Z" fill="#334155" />
                        <!-- Tablet Perangkat Admin -->
                        <rect x="74" y="80" width="22" height="28" rx="3" fill="#0F172A"
                            stroke="#64748B" stroke-width="1" transform="rotate(12 74 80)" />
                        <circle cx="82" cy="85" r="2" fill="#10B981" />

                        <!-- Ikat Pinggang -->
                        <rect x="12" y="112" width="62" height="7" fill="#0F172A" />
                        <rect x="39" y="112" width="8" height="7" rx="1" fill="#94A3B8" />

                        <!-- Celana Formal Hitam / Gelap -->
                        <path d="M12 119 L10 170 L38 170 L41 119 Z" fill="#0F172A" />
                        <path d="M45 119 L48 170 L76 170 L74 119 Z" fill="#0F172A" />

                        <!-- Sepatu Pantofel Mengkilap -->
                        <path d="M6 168 C6 164 16 162 38 164 L38 172 L6 172 Z" fill="#020617" />
                        <path d="M48 164 C70 162 80 164 80 168 L80 172 L48 172 Z" fill="#020617" />
                    </g>
                </svg>
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

                <form action="/prosesloginadmin" method="POST" id="formLoginAdmin" autocomplete="off">
                    @csrf

                    <!-- Input 1: Username / Email Admin -->
                    <div class="input-pill">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
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
                <div class="mobile-badge">
                    Admin Portal
                </div>
                <h3 class="mobile-info-title">PRESIS ADMIN</h3>
                <p class="mobile-info-desc">
                    Panel Kontrol &amp; Pengelolaan Presensi Online BPKUK Prov. Kalsel
                </p>

                <!-- Mini Illustration for Mobile -->
                <div class="mobile-illustration">
                    <svg viewBox="0 0 340 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="170" cy="170" rx="120" ry="8" fill="#0A0F1D"
                            opacity="0.3" />
                        <!-- Centered Monitor with Server Graph -->
                        <g transform="translate(105, 10)">
                            <rect width="130" height="120" rx="14" fill="#1E293B" stroke="#64748B"
                                stroke-width="1.8" />
                            <rect x="6" y="6" width="118" height="108" rx="10" fill="#0F172A" />
                            <rect x="6" y="6" width="118" height="18" rx="8" fill="#334155" />
                            <circle cx="16" cy="15" r="3" fill="#10B981" />
                            <text x="65" y="18" fill="#F8FAFC" font-size="7.5" font-weight="600"
                                text-anchor="middle" font-family="'Poppins', sans-serif">ADMINISTRATOR</text>
                            <!-- Mini Bars -->
                            <rect x="20" y="55" width="12" height="42" rx="2" fill="#475569" />
                            <rect x="38" y="42" width="12" height="55" rx="2" fill="#64748B" />
                            <rect x="56" y="32" width="12" height="65" rx="2" fill="#38BDF8" />
                            <rect x="74" y="24" width="12" height="73" rx="2" fill="#10B981" />
                            <rect x="92" y="48" width="12" height="49" rx="2" fill="#475569" />
                            <rect x="58" y="120" width="14" height="14" fill="#475569" />
                            <path d="M46 134 L84 134 L88 139 L42 139 Z" fill="#334155" />
                        </g>
                    </svg>
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
