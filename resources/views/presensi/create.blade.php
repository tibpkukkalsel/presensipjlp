@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture video {
            display: block;
            width: 100% !important;
            margin: auto;
            height: 100% !important;
            object-fit: cover !important;
            transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
        }

        #map {
            height: 200px;
            border-radius: 14px;
        }



        /* Minimalist Presensi Type Selector */
        .presensi-type-group {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
        }

        .btn-type-tab {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 8px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-type-tab:hover {
            border-color: #cbd5e1;
        }

        .btn-type-tab .type-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .btn-type-tab .type-clock {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
            font-weight: 500;
        }

        .btn-type-tab.active-in {
            border-color: #16a34a !important;
            background: #f0fdf4 !important;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.15) !important;
        }

        .btn-type-tab.active-in .type-name {
            color: #15803d !important;
        }

        .btn-type-tab.active-in .type-clock {
            color: #166534 !important;
            font-weight: 600;
        }

        .btn-type-tab.active-out {
            border-color: #dc2626 !important;
            background: #fef2f2 !important;
            box-shadow: 0 3px 10px rgba(220, 38, 38, 0.15) !important;
        }

        .btn-type-tab.active-out .type-name {
            color: #b91c1c !important;
        }

        .btn-type-tab.active-out .type-clock {
            color: #991b1b !important;
            font-weight: 600;
        }

        .btn-type-tab.is-completed {
            background: #f8fafc;
            border-color: #cbd5e1;
            position: relative;
        }

        .btn-type-tab.is-completed .type-name {
            color: #334155;
        }

        .btn-type-tab.is-completed .type-clock {
            color: #059669;
            font-weight: 600;
        }

        .btn-type-tab.is-completed-out .type-clock {
            color: #dc2626;
            font-weight: 600;
        }

        /* Camera Wrapper & Face Guide: 1:1 Bujur Sangkar Simetris */
        .camera-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 20px;
            overflow: hidden;
            background: #0f172a;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .webcam-capture {
            width: 100% !important;
            height: 100% !important;
            position: absolute !important;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Camera Live Date & Time Overlay */
        .camera-datetime-badge {
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 30px;
            padding: 5px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            pointer-events: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            max-width: 90%;
            white-space: nowrap;
        }

        .camera-datetime-badge .cam-date {
            font-size: 11px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.25;
            letter-spacing: 0.2px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
        }

        .camera-datetime-badge .cam-time {
            font-size: 14.5px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
            margin-top: 1px;
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            letter-spacing: 0.3px;
        }

        .face-guide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .face-oval {
            width: 200px;
            height: 250px;
            border-radius: 50% / 46%;
            border: 2.5px dashed rgba(255, 255, 255, 0.75);
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.22);
        }

        .face-oval.detected {
            border: 2.5px solid #2A8BF2;
        }

        .face-oval.verified {
            border: 3px solid #22c55e !important;
        }

        @keyframes pulseScale {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Face-API Client-side ML for Blink & Smile Liveness Detection -->
    @if ($scan_aktif)
    <script src="{{ asset('assets/js/face-api/face-api.min.js') }}"></script>
    <script>
        if (typeof faceapi === 'undefined') {
            document.write('<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"><\/script>');
        }
    </script>
    @endif
@endsection

@section('content')
    <div style="padding-top: 75px; padding-bottom: 30px;">
        <div class="row">
        <div class="col-12">

            @php
                $daftar_hari = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
                $daftar_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $nama_hari = $daftar_hari[date('D', strtotime($hariini))] ?? date('l', strtotime($hariini));
                $tgl_indo = $nama_hari . ', ' . date('d', strtotime($hariini)) . ' ' . ($daftar_bulan[(int)date('m', strtotime($hariini))] ?? date('F', strtotime($hariini))) . ' ' . date('Y', strtotime($hariini));

                $sudah_masuk = ($datapresensi != null && !empty($datapresensi->jam_in));
                $jam_masuk_pegawai = $sudah_masuk ? date('H:i', strtotime($datapresensi->jam_in)) : null;

                $sudah_pulang = ($datapresensi != null && !empty($datapresensi->jam_out));
                $jam_pulang_pegawai = $sudah_pulang ? date('H:i', strtotime($datapresensi->jam_out)) : null;

                $lengkap_hariini = ($sudah_masuk && $sudah_pulang);
            @endphp

            @if ($lengkap_hariini)
                <!-- Kartu Informasi: Presensi Hari Ini Sudah Lengkap -->
                <div class="card shadow-sm border-0 mb-3" style="border-radius: 16px; background: #ffffff;">
                    <div class="card-body text-center py-4 px-3">
                        <div style="width: 58px; height: 58px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto;">
                            <ion-icon name="checkmark-done-circle" style="font-size: 34px;"></ion-icon>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-1" style="font-size: 16px;">Presensi Hari Ini Selesai</h4>
                        <p class="text-muted mb-3" style="font-size: 13px; line-height: 1.5;">
                            Anda sudah melengkapi seluruh presensi hari ini.<br>
                            Masuk: <strong class="text-success">{{ $jam_masuk_pegawai }} WITA</strong> &bull; Pulang: <strong class="text-danger">{{ $jam_pulang_pegawai }} WITA</strong>
                        </p>
                        <a href="/dashboard" class="btn btn-primary btn-block" style="border-radius: 12px; font-weight: 600; padding: 11px;">
                            <ion-icon name="home-outline" class="mr-1" style="vertical-align: -2px;"></ion-icon> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            @else
                <!-- 1. Selection: Masuk Kerja / Pulang Kerja -->
                <div class="presensi-type-group">
                    <button type="button" class="btn-type-tab {{ $sudah_masuk ? 'is-completed' : '' }}" id="btnPilihMasuk" data-status="in">
                        <span class="type-name d-flex align-items-center justify-content-center" style="gap: 4px;">
                            @if ($sudah_masuk)
                                <ion-icon name="checkmark-circle" style="color: #16a34a; font-size: 15px;"></ion-icon>
                            @endif
                            Masuk Kerja
                        </span>
                        <span class="type-clock">
                            @if ($sudah_masuk)
                                <span class="text-success font-weight-bold">Selesai ({{ $jam_masuk_pegawai }} WITA)</span>
                            @else
                                {{ date('H:i', strtotime($jamkerja->jam_masuk)) }} WITA
                            @endif
                        </span>
                    </button>
                    <button type="button" class="btn-type-tab {{ $sudah_pulang ? 'is-completed is-completed-out' : '' }}" id="btnPilihPulang" data-status="out">
                        <span class="type-name d-flex align-items-center justify-content-center" style="gap: 4px;">
                            @if ($sudah_pulang)
                                <ion-icon name="checkmark-circle" style="color: #dc2626; font-size: 15px;"></ion-icon>
                            @endif
                            Pulang Kerja
                        </span>
                        <span class="type-clock">
                            @if ($sudah_pulang)
                                <span class="text-danger font-weight-bold">Selesai ({{ $jam_pulang_pegawai }} WITA)</span>
                            @else
                                {{ date('H:i', strtotime($jamkerja->jam_pulang)) }} WITA
                            @endif
                        </span>
                    </button>
                </div>

                <!-- Panduan Memilih Masuk / Pulang (Muncul sebelum memilih) -->
                <div id="instructionChoose" class="text-center p-4 mb-3" style="background: #ffffff; border-radius: 14px; border: 1.5px dashed #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #eff6ff; color: #2A8BF2; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto;">
                        <ion-icon name="finger-print-outline" style="font-size: 26px;"></ion-icon>
                    </div>
                    <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">
                        @if ($sudah_masuk)
                            Presensi Pulang Kerja
                        @else
                            Silakan Pilih Presensi
                        @endif
                    </div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        @if ($sudah_masuk)
                            Anda sudah melakukan presensi masuk (<strong>{{ $jam_masuk_pegawai }} WITA</strong>). Ketuk tombol <strong>Pulang Kerja</strong> di atas untuk membuka kamera presensi pulang.
                        @else
                            Ketuk tombol <strong>Masuk Kerja</strong> atau <strong>Pulang Kerja</strong> di atas untuk membuka kamera &amp; lokasi presensi.
                        @endif
                    </div>
                </div>
            @endif

            <!-- Area Kamera dan Map (Hanya Muncul Setelah Memilih Masuk / Pulang) -->
            <div id="presensiContentArea" style="display: none;">
                <!-- 2. Camera View & Face Guide Overlay -->
                <input type="hidden" id="lokasi">
                <div class="camera-wrapper">
                    <div class="webcam-capture"></div>

                    <!-- Overlay Tanggal & Jam Presensi Transparan -->
                    <div class="camera-datetime-badge">
                        <div class="cam-date">{{ $tgl_indo }}</div>
                        <div class="cam-time" id="jam">{{ date('H:i:s') }} WITA</div>
                    </div>

                    @if ($scan_aktif)
                    <!-- Face Oval Guide Overlay (Sederhana Tanpa Badge) -->
                    <div class="face-guide-overlay">
                        <div class="face-oval" id="faceOval"></div>
                    </div>

                    <!-- Visual Countdown Overlay (Jeda Waktu Berfoto) -->
                    <div id="countdownOverlay" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 25; align-items: center; justify-content: center; pointer-events: none;">
                        <div id="countdownBadge" style="width: 88px; height: 88px; border-radius: 50%; background: rgba(0, 0, 0, 0.45); backdrop-filter: blur(4px); border: 2.5px solid rgba(255, 255, 255, 0.85); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 20px rgba(0,0,0,0.35);">
                            <span id="countdownNumber" style="font-size: 44px; font-weight: 900; color: #ffffff; line-height: 1; text-shadow: 0 2px 8px rgba(0,0,0,0.5);">2</span>
                        </div>
                    </div>
                    @endif

                    <!-- Camera Flash Overlay Effect -->
                    <div id="cameraFlash" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #ffffff; z-index: 35; pointer-events: none;"></div>
                </div>

                @if ($scan_aktif)
                <!-- Keterangan Status Senyum untuk Presensi Otomatis -->
                <div class="d-flex justify-content-center mt-2 mb-1">
                    <span id="pillSmile" class="badge bg-light border text-muted px-3 py-2" style="font-size: 13px; font-weight: 600; border-radius: 10px; display: inline-flex; align-items: center; gap: 6px;">
                        <ion-icon name="happy-outline" style="font-size: 18px; color: #f59e0b;"></ion-icon>
                        <span>Silakan Tersenyum:</span> <strong id="badgeSmile" style="color: #64748b;">Belum</strong>
                    </span>
                </div>
                @else
                <!-- Tombol Shutter Foto Manual (Mode Scan OFF) -->
                <div class="mt-3 mb-2">
                    <button type="button" class="btn btn-primary btn-block btn-lg shadow-sm w-100" id="btnShutterPresensi" style="border-radius: 14px; font-weight: 700; font-size: 15px; padding: 13px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <ion-icon name="camera" style="font-size: 22px;"></ion-icon>
                        <span id="btnShutterText">Ambil Foto Presensi</span>
                    </button>
                    <div class="text-center mt-2">
                        <span class="badge badge-secondary" style="font-size: 11px; font-weight: 500; background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; border-radius: 20px; padding: 4px 12px;">
                            <ion-icon name="camera-outline" style="vertical-align: -1px; margin-right: 3px;"></ion-icon>Mode Foto Manual (Scan Dinonaktifkan)
                        </span>
                    </div>
                </div>
                @endif

                <!-- Map Container -->
                <div class="row mt-2">
                    <div class="col">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <!-- Audios -->
    <audio id="notifikasi_in">
        <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="radius_sound">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = set(d.getHours());
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            if (e) {
                e.innerHTML = h + ':' + m + ':' + s + ' WITA';
            }

            setTimeout(jam, 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        var scanAktif = {{ $scan_aktif ? 'true' : 'false' }};
        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');
        var radius_sound = document.getElementById('radius_sound');

        // State Presensi (Pilihan Masuk vs Pulang)
        // Dimulai dari null karena pegawai HARUS memilih dulu sebelum scan!
        var selectedStatus = null;
        var isSubmitting = false;
        var isModelLoaded = false;
        var isScanningActive = true;

        // Liveness State: Cukup senyum
        var smileVerified = false;

        // Web Audio Context for Instant Chimes
        function playChime(freq, type, duration) {
            try {
                var AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                var ctx = new AudioContext();
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = type || 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + (duration || 0.15));
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + (duration || 0.15));
            } catch (err) {
                // Ignore silent audio block
            }
        }

        // Efek Kilatan Blitz Kamera (Camera Flash)
        function triggerCameraFlash() {
            var flash = document.getElementById('cameraFlash');
            if (flash) {
                flash.style.display = 'block';
                flash.style.opacity = '0.92';
                setTimeout(function() {
                    flash.style.transition = 'opacity 0.28s ease-out';
                    flash.style.opacity = '0';
                    setTimeout(function() {
                        flash.style.display = 'none';
                        flash.style.opacity = '0.92';
                        flash.style.transition = '';
                    }, 280);
                }, 40);
            }
        }

        // Suara Shutter Kamera "Cekrek!"
        function playShutterSound() {
            try {
                var AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                var ctx = new AudioContext();

                var osc1 = ctx.createOscillator();
                var gain1 = ctx.createGain();
                osc1.type = 'triangle';
                osc1.frequency.setValueAtTime(1400, ctx.currentTime);
                osc1.frequency.exponentialRampToValueAtTime(300, ctx.currentTime + 0.04);
                gain1.gain.setValueAtTime(0.25, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.04);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start();
                osc1.stop(ctx.currentTime + 0.04);

                setTimeout(function() {
                    try {
                        var osc2 = ctx.createOscillator();
                        var gain2 = ctx.createGain();
                        osc2.type = 'sine';
                        osc2.frequency.setValueAtTime(900, ctx.currentTime);
                        osc2.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.05);
                        gain2.gain.setValueAtTime(0.25, ctx.currentTime);
                        gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.05);
                        osc2.connect(gain2);
                        gain2.connect(ctx.destination);
                        osc2.start();
                        osc2.stop(ctx.currentTime + 0.05);
                    } catch (e2) {}
                }, 55);
            } catch (e) {}
        }

        // State Presensi Hari Ini
        var sudahMasuk = {{ $sudah_masuk ? 'true' : 'false' }};
        var jamMasukPegawai = "{{ $jam_masuk_pegawai ?? '' }}";
        var sudahPulang = {{ $sudah_pulang ? 'true' : 'false' }};
        var jamPulangPegawai = "{{ $jam_pulang_pegawai ?? '' }}";
        var lengkapHariIni = {{ $lengkap_hariini ? 'true' : 'false' }};

        if (!lengkapHariIni) {
            // Setup Webcam: Bujur Sangkar 1:1
            Webcam.set({
                width: 480,
                height: 480,
                dest_width: 480,
                dest_height: 480,
                image_format: 'jpeg',
                jpeg_quality: 90,
                flip_horiz: false, // Mirroring ditangani langsung oleh CSS video & canvas capture
                constraints: {
                    video: {
                        facingMode: 'user',
                        width: { ideal: 720 },
                        height: { ideal: 720 }
                    }
                }
            });

            Webcam.attach('.webcam-capture');

            // Location / GPS
            var lokasi = document.getElementById('lokasi');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                Swal.fire({
                    title: 'GPS Tidak Didukung',
                    text: 'Browser atau perangkat Anda tidak mendukung fitur lokasi GPS.',
                    icon: 'error'
                });
            }
        }

        var mapInstance = null;
        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            if (!mapInstance) {
                mapInstance = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                var lokasi_kantor = "{{ $lok_kantor->lokasi_cabang }}";
                var lok = lokasi_kantor.split(",");
                var lat_kantor = lok[0];
                var long_kantor = lok[1];
                var radius = "{{ $lok_kantor->radius_cabang }}";
                L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                }).addTo(mapInstance);
                var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(mapInstance);
                var circle = L.circle([lat_kantor, long_kantor], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: radius
                }).addTo(mapInstance);
            }
        }

        function errorCallback(error) {
            var msg = 'Gagal mendeteksi lokasi GPS.';
            if (error) {
                if (error.code == 1) {
                    msg = 'Akses lokasi ditolak. Harap izinkan akses lokasi (GPS) pada browser Anda.';
                } else if (error.code == 2) {
                    msg = 'Titik lokasi GPS tidak dapat ditentukan oleh perangkat Anda.';
                } else if (error.code == 3) {
                    msg = 'Waktu permintaan lokasi GPS habis (timeout). Pastikan GPS aktif.';
                }
            }
            Swal.fire({
                title: 'Perhatian!',
                text: msg,
                icon: 'warning'
            });
        }

        // Mode Switcher Buttons (Masuk vs Pulang)
        function updateModeUI(mode) {
            selectedStatus = mode;

            // Tampilkan Kamera & Map setelah memilih
            $("#instructionChoose").slideUp(200);
            $("#presensiContentArea").slideDown(300, function() {
                if (mapInstance) {
                    mapInstance.invalidateSize();
                }
            });

            if (mode === 'in') {
                $("#btnPilihMasuk").addClass('active-in');
                $("#btnPilihPulang").removeClass('active-out');
            } else {
                $("#btnPilihPulang").addClass('active-out');
                $("#btnPilihMasuk").removeClass('active-in');
            }

            if (!scanAktif) {
                if (mode === 'in') {
                    $("#btnShutterPresensi").removeClass('btn-primary btn-danger').addClass('btn-success');
                    $("#btnShutterText").text('Ambil Foto Masuk Kerja');
                } else {
                    $("#btnShutterPresensi").removeClass('btn-primary btn-success').addClass('btn-danger');
                    $("#btnShutterText").text('Ambil Foto Pulang Kerja');
                }
            }

            // Audio & Haptic feedback saat memilih
            playChime(520, 'sine', 0.12);
            if (navigator.vibrate) {
                try {
                    navigator.vibrate(35);
                } catch (e) {}
            }

            if (scanAktif) {
                resetLiveness();
            }

            setTimeout(function() {
                if (mapInstance) {
                    mapInstance.invalidateSize();
                }
            }, 400);
        }

        $("#btnPilihMasuk").click(function(e) {
            if (sudahMasuk) {
                e.preventDefault();
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Anda sudah berhasil melakukan presensi masuk hari ini',
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
                return false;
            }
            updateModeUI('in');
        });

        $("#btnPilihPulang").click(function(e) {
            if (sudahPulang) {
                e.preventDefault();
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Anda sudah berhasil melakukan presensi pulang hari ini',
                    icon: 'info',
                    confirmButtonText: 'Tutup'
                });
                return false;
            }
            updateModeUI('out');
        });

        // Reset Liveness State
        function resetLiveness() {
            if (!scanAktif) return;

            smileVerified = false;
            $("#countdownOverlay").hide();
            $("#faceOval").removeClass('verified detected');
            $("#pillSmile").removeClass('bg-success text-white border-success').addClass('bg-light text-muted');
            $("#badgeSmile").css('color', '#64748b').text('Belum');
        }

        function setDirective(text, showSpinner) {
            // Sederhana tanpa badge melayang di atas kamera
        }

        // Load Face API Models (TinyFaceDetector + FaceExpressions)
        async function loadModels() {
            try {
                var modelUrl = "{{ asset('assets/models') }}";
                await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);
                await faceapi.nets.faceExpressionNet.loadFromUri(modelUrl);
                isModelLoaded = true;
            } catch (err) {
                console.warn("Gagal memuat model lokal face-api:", err);
                try {
                    var cdnUrl = "https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model";
                    await faceapi.nets.tinyFaceDetector.loadFromUri(cdnUrl);
                    await faceapi.nets.faceExpressionNet.loadFromUri(cdnUrl);
                    isModelLoaded = true;
                } catch (cdnErr) {
                    console.error("Gagal memuat model CDN:", cdnErr);
                }
            }
        }

        // Start Detection Loop: Cukup Deteksi Senyum
        async function startFaceScanningLoop() {
            if (!isScanningActive || isSubmitting) {
                setTimeout(startFaceScanningLoop, 200);
                return;
            }

            // Wajib memilih jenis presensi dulu sebelum memindai & verifikasi
            if (!selectedStatus) {
                setTimeout(startFaceScanningLoop, 200);
                return;
            }

            var videoEl = document.querySelector('.webcam-capture video');
            if (!videoEl || videoEl.paused || videoEl.ended || !isModelLoaded || videoEl.readyState < 2) {
                setTimeout(startFaceScanningLoop, 150);
                return;
            }

            try {
                var options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.40 });
                var detection = await faceapi.detectSingleFace(videoEl, options)
                    .withFaceExpressions();

                if (detection) {
                    $("#faceOval").addClass('detected');

                    // Check Smile: Cukup Senyum Saja dengan Jeda Hitungan Mundur (2... 1... Cekrek!)
                    if (!smileVerified) {
                        var happyScore = (detection.expressions && detection.expressions.happy) ? detection.expressions.happy : 0;
                        if (happyScore > 0.45) {
                            smileVerified = true;
                            isScanningActive = false;

                            $("#pillSmile").removeClass('bg-light text-muted').addClass('bg-success text-white border-success');
                            $("#badgeSmile").css('color', '#ffffff').text('Tahan Senyum (2)');
                            $("#faceOval").removeClass('detected').addClass('verified');

                            // Ding konfirmasi awal senyum terdeteksi
                            playChime(850, 'sine', 0.20);

                            // Tampilkan Countdown Angka 2
                            var $overlay = $("#countdownOverlay");
                            var $num = $("#countdownNumber");
                            $overlay.css('display', 'flex');
                            $num.text('2').css('animation', 'none');
                            if ($num[0]) void $num[0].offsetWidth;
                            $num.css('animation', 'pulseScale 0.7s ease');

                            // Detik ke-1: Angka 1
                            setTimeout(function() {
                                $("#badgeSmile").text('Tahan Senyum (1)');
                                playChime(950, 'sine', 0.15);
                                $num.text('1').css('animation', 'none');
                                if ($num[0]) void $num[0].offsetWidth;
                                $num.css('animation', 'pulseScale 0.7s ease');
                            }, 850);

                            // Ambil Foto Presensi Secara Halus & Elegan
                            setTimeout(function() {
                                $overlay.hide();
                                $("#badgeSmile").text('Foto Terambil ✓');
                                triggerCameraFlash();
                                playShutterSound();

                                setTimeout(function() {
                                    executeAttendance(selectedStatus);
                                }, 300);
                            }, 1750);

                            return;
                        }
                    }
                } else {
                    $("#faceOval").removeClass('detected');
                }
            } catch (scanErr) {
                console.error("Scanning error:", scanErr);
            }

            setTimeout(startFaceScanningLoop, 120);
        }

        // Execute Attendance Submission
        // Pengambilan Foto Presensi: 100% Simetris 1:1 Bujur Sangkar Bebas Distorsi (Anti-Gepeng)
        function captureSymmetricPhoto() {
            var video = document.querySelector('.webcam-capture video');
            if (video && video.videoWidth > 0 && video.videoHeight > 0) {
                var vw = video.videoWidth;
                var vh = video.videoHeight;

                // Ambil ukuran sisi terkecil untuk bujur sangkar 1:1 murni
                var side = Math.min(vw, vh);

                // Potong tepat dari tengah (center-crop)
                var sx = Math.floor((vw - side) / 2);
                var sy = Math.floor((vh - side) / 2);

                // Canvas bujur sangkar resolusi tajam 480x480
                var canvas = document.createElement('canvas');
                canvas.width = 480;
                canvas.height = 480;
                var ctx = canvas.getContext('2d');

                // Mirror selfie horizontal agar hasil foto sesuai cermin kamera depan
                ctx.translate(480, 0);
                ctx.scale(-1, 1);

                // Gambar 1:1 bujur sangkar langsung dari video mentah tanpa squashing
                ctx.drawImage(video, sx, sy, side, side, 0, 0, 480, 480);

                return canvas.toDataURL('image/jpeg', 0.90);
            }

            // Fallback jika elemen video belum siap
            var fallback = "";
            try {
                Webcam.snap(function(uri) {
                    fallback = uri;
                });
            } catch (err) {
                console.error("Webcam.snap fallback error:", err);
            }
            return fallback;
        }

        function executeAttendance(statusAbsen) {
            if (isSubmitting) return;

            if (!statusAbsen) {
                Swal.fire({
                    title: 'Pilih Jenis Presensi',
                    text: 'Silakan ketuk pilihan Masuk Kerja atau Pulang Kerja terlebih dahulu sebelum scan!',
                    icon: 'warning',
                    confirmButtonText: 'Mengerti'
                });
                return false;
            }

            if (statusAbsen === 'in' && sudahMasuk) {
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Anda sudah berhasil melakukan presensi masuk hari ini',
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                return false;
            }

            if (statusAbsen === 'out' && sudahPulang) {
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Anda sudah berhasil melakukan presensi pulang hari ini',
                    icon: 'warning',
                    confirmButtonText: 'Tutup'
                });
                return false;
            }

            isSubmitting = true;

            var image = captureSymmetricPhoto();

            if (!image) {
                Swal.fire({
                    title: 'Kamera Belum Siap',
                    text: 'Pastikan kamera aktif dan berikan izin akses kamera pada browser Anda.',
                    icon: 'warning'
                });
                isSubmitting = false;
                isScanningActive = true;
                return false;
            }

            var lokasiVal = $("#lokasi").val();
            if (!lokasiVal) {
                Swal.fire({
                    title: 'Lokasi Belum Terdeteksi',
                    text: 'Sedang mencari titik koordinat GPS Anda. Pastikan GPS aktif dan izinkan akses lokasi pada browser.',
                    icon: 'warning'
                });
                isSubmitting = false;
                isScanningActive = true;
                return false;
            }

            // Tampilkan Loading Swal
            Swal.fire({
                title: 'Menyimpan Presensi...',
                text: 'Sedang mengirim data foto dan lokasi ' + (statusAbsen === 'in' ? 'Masuk' : 'Pulang'),
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasiVal,
                    kode_jam_kerja: "{{ $kode_jam_kerja }}",
                    status_absen: statusAbsen
                },
                cache: false,
                success: function(respond) {
                    var status = respond.split("|");
                    if (status[0] == "success") {
                        if (status[2] == "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }
                        Swal.fire({
                            title: 'Berhasil !',
                            text: status[1],
                            icon: 'success'
                        });
                        setTimeout(function() {
                            location.href = '/dashboard';
                        }, 2500);
                    } else {
                        isSubmitting = false;
                        isScanningActive = true;
                        if (status[2] == "radius") {
                            radius_sound.play();
                        }
                        Swal.fire({
                            title: 'Error !',
                            text: status[1],
                            icon: 'error'
                        });
                        resetLiveness();
                    }
                },
                error: function(xhr, status, error) {
                    isSubmitting = false;
                    isScanningActive = true;
                    Swal.fire({
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal memproses presensi (' + (xhr.status || '') + ' ' + (xhr.statusText || error) + '). Silakan hubungi admin / IT.',
                        icon: 'error'
                    });
                    resetLiveness();
                }
            });
        }

        // Tombol Shutter Foto Manual saat Scan Nonaktif
        $("#btnShutterPresensi").click(function() {
            if (!selectedStatus) {
                Swal.fire({
                    title: 'Pilih Jenis Presensi',
                    text: 'Silakan ketuk pilihan Masuk Kerja atau Pulang Kerja terlebih dahulu!',
                    icon: 'warning',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }
            triggerCameraFlash();
            playShutterSound();
            setTimeout(function() {
                executeAttendance(selectedStatus);
            }, 250);
        });

        // Initialize on Document Ready
        $(document).ready(function() {
            if (!lengkapHariIni && scanAktif) {
                loadModels().then(function() {
                    startFaceScanningLoop();
                });
            }
        });
    </script>
@endpush
