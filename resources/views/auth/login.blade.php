<!doctype html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#2A8BF2">
    <title>PRESIS - Sign In</title>
    <meta name="description" content="Aplikasi E-Presensi dan Laporan Penerimaan BPKUK Prov. Kalsel">
    <link rel="icon" type="image/png" href="{{ asset('Logo Presis.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Logo Presis.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom Login Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>

<body>

    <div class="page-container">

        <!-- ================= DESKTOP LEFT PANEL (Curved Blue Wave) ================= -->
        <div class="panel-side">
            <!-- SVG Wave Overlay creating smooth organic curve into white area -->
            <svg class="desktop-wave" viewBox="0 0 100 500" preserveAspectRatio="none" fill="#FFFFFF">
                <path d="M 100 0 L 100 500 L 0 500 C 70 380, 85 240, 15 110 C -5 70, 0 30, 20 0 Z" />
            </svg>

            <!-- Text Content -->
            <div class="side-content">
                <h2 class="side-title">PRESIS</h2>
                <p class="side-desc">
                    Selamat datang di Sistem Presensi &amp; Pelaporan Pegawai BPKUK Prov. Kalsel. Silahkan masuk
                    menggunakan Username Anda.
                </p>
            </div>

            <!-- Online Attendance & Employee in White PDH Uniform Illustration -->
            <div class="side-illustration">
                <svg viewBox="0 0 340 225" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Ground Base Shadow -->
                    <ellipse cx="170" cy="214" rx="125" ry="9" fill="#0D47A1"
                        opacity="0.3" />

                    <!-- Radar / Geofencing concentric waves -->
                    <circle cx="88" cy="112" r="50" stroke="rgba(255,255,255,0.2)" stroke-dasharray="4 4"
                        fill="none" />
                    <circle cx="88" cy="112" r="75" stroke="rgba(255,255,255,0.12)" stroke-dasharray="5 5"
                        fill="none" />

                    <!-- Floating Top Badges -->

                    <!-- Left: Smartphone Presensi Online -->
                    <g transform="translate(36, 36)">
                        <!-- Phone Outer Shadow -->
                        <rect x="0" y="4" width="102" height="166" rx="20" fill="#0A192F" opacity="0.3" />

                        <!-- Phone Bezel -->
                        <rect x="0" y="0" width="102" height="166" rx="20" fill="#0F172A" stroke="#38BDF8"
                            stroke-width="2.2" />

                        <!-- Screen Glass -->
                        <rect x="5" y="6" width="92" height="154" rx="15" fill="#1E293B" />

                        <!-- Top Speaker Notch -->
                        <rect x="35" y="9" width="32" height="4" rx="2" fill="#334155" />

                        <!-- Screen App Header -->
                        <rect x="5" y="16" width="92" height="24" fill="#2563EB" />
                        <circle cx="16" cy="28" r="4" fill="#60A5FA" />
                        <text x="52" y="32" fill="#FFFFFF" font-size="8.5" font-weight="700" text-anchor="middle"
                            font-family="'Poppins', sans-serif">PRESIS ONLINE</text>

                        <!-- Face Recognition Scanner Frame -->
                        <g transform="translate(13, 46)">
                            <!-- Scan Area Background -->
                            <rect width="66" height="66" rx="10" fill="#0F172A" stroke="#0284C7"
                                stroke-width="1" />

                            <!-- Face Silhouette inside phone camera -->
                            <circle cx="33" cy="27" r="13" fill="#64748B" />
                            <path d="M17 55 C17 44 24 40 33 40 C42 40 49 44 49 55 Z" fill="#64748B" />

                            <!-- Scan Laser Line -->
                            <line x1="6" y1="33" x2="60" y2="33" stroke="#38BDF8"
                                stroke-width="1.5" stroke-dasharray="3 3" opacity="0.8" />

                            <!-- Corner Brackets -->
                            <path d="M 5 12 L 5 5 L 12 5" stroke="#10B981" stroke-width="2.2" stroke-linecap="round"
                                fill="none" />
                            <path d="M 61 12 L 61 5 L 54 5" stroke="#10B981" stroke-width="2.2"
                                stroke-linecap="round" fill="none" />
                            <path d="M 5 54 L 5 61 L 12 61" stroke="#10B981" stroke-width="2.2"
                                stroke-linecap="round" fill="none" />
                            <path d="M 61 54 L 61 61 L 54 61" stroke="#10B981" stroke-width="2.2"
                                stroke-linecap="round" fill="none" />
                        </g>

                        <!-- Success Badge -->
                        <g transform="translate(13, 118)">
                            <rect width="66" height="28" rx="8" fill="#10B981" />
                            <circle cx="15" cy="14" r="8" fill="#FFFFFF" />
                            <path d="M11.5 14 L14 16.5 L18.5 11.5" stroke="#10B981" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" fill="none" />
                            <text x="42" y="13" fill="#FFFFFF" font-size="7.5" font-weight="700"
                                text-anchor="middle" font-family="'Poppins', sans-serif">BERHASIL</text>
                            <text x="42" y="22" fill="#E6FFFA" font-size="6.5" font-weight="500"
                                text-anchor="middle" font-family="'Poppins', sans-serif">HADIR TEPAT</text>
                        </g>
                    </g>

                    <!-- Location Pin with Signal Waves -->
                    <g transform="translate(16, 16)">
                        <path d="M18 16 C12 16 8 20 8 26 C8 34 18 42 18 42 C18 42 28 34 28 26 C28 20 24 16 18 16 Z"
                            fill="#EF4444" />
                        <circle cx="18" cy="24" r="3.5" fill="#FFFFFF" />
                        <!-- Radiating rings -->
                        <circle cx="18" cy="24" r="10" stroke="#38BDF8" stroke-width="1.5"
                            stroke-dasharray="2 3" fill="none" opacity="0.8" />
                    </g>

                    <!-- Right: Pegawai Pria Berbaju PDH Putih ASN -->
                    <g transform="translate(168, 40)">
                        <!-- Head & Neck -->
                        <rect x="36" y="32" width="14" height="14" fill="#FCD34D" rx="2" />
                        <!-- Face -->
                        <ellipse cx="43" cy="22" rx="14" ry="16" fill="#FCD34D" />
                        <!-- Formal Hair -->
                        <path d="M28 20 C28 5 58 5 58 20 C58 12 48 8 40 10 C33 12 30 16 28 20 Z" fill="#1E293B" />
                        <!-- Smile & Eyes -->
                        <circle cx="38" cy="20" r="1.5" fill="#1E293B" />
                        <circle cx="48" cy="20" r="1.5" fill="#1E293B" />
                        <path d="M40 26 Q43 29 46 26" stroke="#B45309" stroke-width="1.2" stroke-linecap="round"
                            fill="none" />

                        <!-- Baju PDH Putih (White Uniform) -->
                        <!-- Shirt Body -->
                        <path d="M16 44 L70 44 L74 114 L12 114 Z" fill="#FFFFFF" />
                        <!-- Shading / Fabric Depth -->
                        <path d="M62 44 L70 44 L74 114 L66 114 Z" fill="#E2E8F0" />
                        <path d="M16 44 L22 44 L18 114 L12 114 Z" fill="#E2E8F0" />
                        <line x1="43" y1="56" x2="43" y2="114" stroke="#CBD5E1"
                            stroke-width="1.5" />
                        <!-- Buttons -->
                        <circle cx="43" cy="68" r="1.5" fill="#94A3B8" />
                        <circle cx="43" cy="82" r="1.5" fill="#94A3B8" />
                        <circle cx="43" cy="96" r="1.5" fill="#94A3B8" />

                        <!-- Crisp Collars (Kerah Kemeja Putih) -->
                        <path d="M32 44 L43 56 L37 44 Z" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="0.8" />
                        <path d="M54 44 L43 56 L49 44 Z" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="0.8" />

                        <!-- Tanda Pangkat / Lidah Pundak Emas (Epaulets) -->
                        <polygon points="15,44 29,45 27,49 13,48" fill="#D97706" />
                        <circle cx="21" cy="46.5" r="1" fill="#FCD34D" />
                        <polygon points="71,44 57,45 59,49 73,48" fill="#D97706" />
                        <circle cx="65" cy="46.5" r="1" fill="#FCD34D" />

                        <!-- Lencana KORPRI Emas (Dada Kiri) -->
                        <rect x="52" y="62" width="9" height="7" rx="1.5" fill="#F59E0B" />
                        <polygon points="56.5,63 59,67 54,67" fill="#FCD34D" />

                        <!-- Papan Nama Dada Hitam (Dada Kanan) -->
                        <rect x="23" y="63" width="14" height="5" rx="1" fill="#0F172A" />
                        <rect x="25" y="64.5" width="10" height="2" fill="#FFFFFF" />

                        <!-- Saku Kemeja Berpenutup -->
                        <rect x="22" y="72" width="16" height="14" rx="2" fill="none"
                            stroke="#E2E8F0" stroke-width="1" />
                        <rect x="48" y="72" width="16" height="14" rx="2" fill="none"
                            stroke="#E2E8F0" stroke-width="1" />

                        <!-- Arms (Lengan Baju Putih) -->
                        <!-- Left arm pointing towards smartphone -->
                        <path d="M16 46 L-4 78 L4 84 L22 56 Z" fill="#FFFFFF" />
                        <path d="M-4 78 L-14 88 L-8 93 L2 84 Z" fill="#FCD34D" />
                        <ellipse cx="-10" cy="88" rx="4" ry="3" fill="#FCD34D" />

                        <!-- Right arm at side holding tablet/folder -->
                        <path d="M70 46 L86 80 L78 85 L64 56 Z" fill="#FFFFFF" />
                        <path d="M86 80 L90 92 L82 94 L78 85 Z" fill="#FCD34D" />
                        <!-- Document folder -->
                        <rect x="76" y="85" width="18" height="24" rx="2" fill="#1D4ED8"
                            transform="rotate(10 76 85)" />

                        <!-- Ikat Pinggang Hitam Kepala Emas -->
                        <rect x="12" y="112" width="62" height="7" fill="#0F172A" />
                        <rect x="39" y="112" width="8" height="7" rx="1" fill="#F59E0B" />

                        <!-- Celana Formal Gelap (Dark Trousers) -->
                        <path d="M12 119 L10 170 L38 170 L41 119 Z" fill="#1E293B" />
                        <path d="M45 119 L48 170 L76 170 L74 119 Z" fill="#1E293B" />

                        <!-- Sepatu Pantofel Hitam Formal -->
                        <path d="M6 168 C6 164 16 162 38 164 L38 172 L6 172 Z" fill="#0F172A" />
                        <path d="M48 164 C70 162 80 164 80 168 L80 172 L48 172 Z" fill="#0F172A" />
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

                <form action="/proseslogin" method="POST" id="formLogin">
                    @csrf

                    <!-- Input 1: Username / NIK -->
                    <div class="input-pill">
                        <div class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input type="text" name="nik" id="nik" class="input-field"
                            placeholder="Username" required autocomplete="username" autofocus>
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
                            onclick="alert('Silahkan hubungi administrator BPKUK Prov. Kalsel untuk reset password.')">Forgot
                            Password?</a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        LOGIN
                    </button>

                </form>

                <div class="form-footer">
                    Balai Pelatihan Koperasi dan Usaha Kecil <br> Provinsi Kalimantan Selatan
                </div>

            </div>
        </div>

        <!-- ================= MOBILE BOTTOM PANEL (Curved Wave & Illustration) ================= -->
        <div class="mobile-bottom-panel">
            <!-- Smooth Curved Wave on Top of Blue Section -->
            <div class="mobile-wave-top">
                <svg viewBox="0 0 500 70" preserveAspectRatio="none" fill="#FFFFFF">
                    <path d="M 0 0 L 500 0 C 360 65, 140 65, 0 0 Z" />
                </svg>
            </div>

            <div class="mobile-info-wrap">
                <h3 class="mobile-info-title">PRESIS</h3>
                <p class="mobile-info-desc">
                    Aplikasi Presensi Online BPKUK Prov. Kalsel.<br>Bapenda Prov. Kalimantan Selatan
                </p>

                <!-- Workspace / Computer Illustration for Mobile -->
                <div class="mobile-illustration">
                    <svg viewBox="0 0 340 225" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Ground Base Shadow -->
                        <ellipse cx="170" cy="214" rx="125" ry="9" fill="#0D47A1"
                            opacity="0.3" />

                        <!-- Radar / Geofencing concentric waves -->
                        <circle cx="88" cy="112" r="50" stroke="rgba(255,255,255,0.2)"
                            stroke-dasharray="4 4" fill="none" />
                        <circle cx="88" cy="112" r="75" stroke="rgba(255,255,255,0.12)"
                            stroke-dasharray="5 5" fill="none" />

                        <!-- Floating Top Badges -->
                        <g transform="translate(100, 10)">
                            <rect width="112" height="22" rx="11" fill="#FFFFFF"
                                filter="drop-shadow(0 3px 6px rgba(0,0,0,0.18))" />
                            <circle cx="12" cy="11" r="5" fill="#10B981" />
                            <path d="M10 11 L12 13 L15 9" stroke="#FFFFFF" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round" fill="none" />
                            <text x="24" y="15" fill="#1E293B" font-size="9" font-weight="700"
                                font-family="'Poppins', sans-serif">GPS Terverifikasi</text>
                        </g>

                        <!-- Left: Smartphone Presensi Online -->
                        <g transform="translate(36, 36)">
                            <rect x="0" y="4" width="102" height="166" rx="20" fill="#0A192F"
                                opacity="0.3" />
                            <rect x="0" y="0" width="102" height="166" rx="20" fill="#0F172A"
                                stroke="#38BDF8" stroke-width="2.2" />
                            <rect x="5" y="6" width="92" height="154" rx="15" fill="#1E293B" />
                            <rect x="35" y="9" width="32" height="4" rx="2" fill="#334155" />
                            <rect x="5" y="16" width="92" height="24" fill="#2563EB" />
                            <circle cx="16" cy="28" r="4" fill="#60A5FA" />
                            <text x="52" y="32" fill="#FFFFFF" font-size="8.5" font-weight="700"
                                text-anchor="middle" font-family="'Poppins', sans-serif">PRESIS ONLINE</text>

                            <!-- Scanner Frame -->
                            <g transform="translate(13, 46)">
                                <rect width="66" height="66" rx="10" fill="#0F172A" stroke="#0284C7"
                                    stroke-width="1" />
                                <circle cx="33" cy="27" r="13" fill="#64748B" />
                                <path d="M17 55 C17 44 24 40 33 40 C42 40 49 44 49 55 Z" fill="#64748B" />
                                <line x1="6" y1="33" x2="60" y2="33" stroke="#38BDF8"
                                    stroke-width="1.5" stroke-dasharray="3 3" opacity="0.8" />
                                <path d="M 5 12 L 5 5 L 12 5" stroke="#10B981" stroke-width="2.2"
                                    stroke-linecap="round" fill="none" />
                                <path d="M 61 12 L 61 5 L 54 5" stroke="#10B981" stroke-width="2.2"
                                    stroke-linecap="round" fill="none" />
                                <path d="M 5 54 L 5 61 L 12 61" stroke="#10B981" stroke-width="2.2"
                                    stroke-linecap="round" fill="none" />
                                <path d="M 61 54 L 61 61 L 54 61" stroke="#10B981" stroke-width="2.2"
                                    stroke-linecap="round" fill="none" />
                            </g>

                            <!-- Success Badge -->
                            <g transform="translate(13, 118)">
                                <rect width="66" height="28" rx="8" fill="#10B981" />
                                <circle cx="15" cy="14" r="8" fill="#FFFFFF" />
                                <path d="M11.5 14 L14 16.5 L18.5 11.5" stroke="#10B981" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                <text x="42" y="13" fill="#FFFFFF" font-size="7.5" font-weight="700"
                                    text-anchor="middle" font-family="'Poppins', sans-serif">BERHASIL</text>
                                <text x="42" y="22" fill="#E6FFFA" font-size="6.5" font-weight="500"
                                    text-anchor="middle" font-family="'Poppins', sans-serif">HADIR TEPAT</text>
                            </g>
                        </g>

                        <!-- Right: Pegawai Baju PDH Putih ASN -->
                        <g transform="translate(168, 40)">
                            <rect x="36" y="32" width="14" height="14" fill="#FCD34D" rx="2" />
                            <ellipse cx="43" cy="22" rx="14" ry="16" fill="#FCD34D" />
                            <path d="M28 20 C28 5 58 5 58 20 C58 12 48 8 40 10 C33 12 30 16 28 20 Z" fill="#1E293B" />
                            <circle cx="38" cy="20" r="1.5" fill="#1E293B" />
                            <circle cx="48" cy="20" r="1.5" fill="#1E293B" />

                            <!-- Baju PDH Putih -->
                            <path d="M16 44 L70 44 L74 114 L12 114 Z" fill="#FFFFFF" />
                            <path d="M62 44 L70 44 L74 114 L66 114 Z" fill="#E2E8F0" />
                            <line x1="43" y1="56" x2="43" y2="114" stroke="#CBD5E1"
                                stroke-width="1.5" />
                            <circle cx="43" cy="68" r="1.5" fill="#94A3B8" />
                            <circle cx="43" cy="82" r="1.5" fill="#94A3B8" />
                            <circle cx="43" cy="96" r="1.5" fill="#94A3B8" />

                            <!-- Kerah & Pangkat Emas -->
                            <path d="M32 44 L43 56 L37 44 Z" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="0.8" />
                            <path d="M54 44 L43 56 L49 44 Z" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="0.8" />
                            <polygon points="15,44 29,45 27,49 13,48" fill="#D97706" />
                            <polygon points="71,44 57,45 59,49 73,48" fill="#D97706" />

                            <!-- Lencana KORPRI & Nama Dada -->
                            <rect x="52" y="62" width="9" height="7" rx="1.5" fill="#F59E0B" />
                            <rect x="23" y="63" width="14" height="5" rx="1" fill="#0F172A" />

                            <!-- Arms & Pants -->
                            <path d="M16 46 L-4 78 L4 84 L22 56 Z" fill="#FFFFFF" />
                            <path d="M-4 78 L-14 88 L-8 93 L2 84 Z" fill="#FCD34D" />
                            <path d="M70 46 L86 80 L78 85 L64 56 Z" fill="#FFFFFF" />
                            <path d="M86 80 L90 92 L82 94 L78 85 Z" fill="#FCD34D" />

                            <rect x="12" y="112" width="62" height="7" fill="#0F172A" />
                            <rect x="39" y="112" width="8" height="7" rx="1" fill="#F59E0B" />

                            <path d="M12 119 L10 170 L38 170 L41 119 Z" fill="#1E293B" />
                            <path d="M45 119 L48 170 L76 170 L74 119 Z" fill="#1E293B" />

                            <path d="M6 168 C6 164 16 162 38 164 L38 172 L6 172 Z" fill="#0F172A" />
                            <path d="M48 164 C70 162 80 164 80 168 L80 172 L48 172 Z" fill="#0F172A" />
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

        // Service Worker Registration for PWA
        var BASE_URL = "{{ url('/') }}/";
        if ('serviceWorker' in navigator && navigator.onLine) {
            navigator.serviceWorker.register(BASE_URL + 'service-worker.js')
                .then(function(reg) {
                    console.log('Service Worker aktif:', reg);
                })
                .catch(function(err) {
                    console.error('Service Worker gagal:', err);
                });
        }
    </script>
</body>

</html>
