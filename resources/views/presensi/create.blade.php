@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 18px;
            object-fit: cover;
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

        /* Camera Wrapper & Face Guide */
        .camera-wrapper {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            background: #000;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
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
            width: 210px;
            height: 270px;
            border-radius: 50% / 46%;
            border: 2px dashed rgba(255, 255, 255, 0.7);
            position: relative;
            transition: all 0.3s ease;
        }

        .face-oval.detected {
            border: 2.5px solid #2A8BF2;
        }

        .face-oval.verified {
            border: 3px solid #22c55e !important;
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
    <div class="row" style="margin-top: 60px">
        <div class="col-12">

            @php
                $daftar_hari = ['Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu'];
                $daftar_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $nama_hari = $daftar_hari[date('D', strtotime($hariini))] ?? date('l', strtotime($hariini));
                $tgl_indo = $nama_hari . ', ' . date('d', strtotime($hariini)) . ' ' . ($daftar_bulan[(int)date('m', strtotime($hariini))] ?? date('F', strtotime($hariini))) . ' ' . date('Y', strtotime($hariini));
            @endphp

            <!-- Badge Ungu Sederhana: Data Tanggal & Jam Berlangsung -->
            <div class="alert alert-info text-center py-2 mb-2 shadow-sm rounded-lg" style="border-radius: 12px;">
                <div style="font-size: 13px; font-weight: 600;">
                    {{ $tgl_indo }}
                </div>
                <div id="jam" style="font-size: 17px; font-weight: 700; margin-top: 1px;">
                    {{ date('H:i:s') }} WITA
                </div>
            </div>

            <!-- 1. Selection: Masuk Kerja / Pulang Kerja -->
            <div class="presensi-type-group">
                <button type="button" class="btn-type-tab" id="btnPilihMasuk" data-status="in">
                    <span class="type-name">Masuk Kerja</span>
                    <span class="type-clock">{{ date('H:i', strtotime($jamkerja->jam_masuk)) }} WITA</span>
                </button>
                <button type="button" class="btn-type-tab" id="btnPilihPulang" data-status="out">
                    <span class="type-name">Pulang Kerja</span>
                    <span class="type-clock">{{ date('H:i', strtotime($jamkerja->jam_pulang)) }} WITA</span>
                </button>
            </div>

            <!-- Panduan Memilih Masuk / Pulang (Muncul sebelum memilih) -->
            <div id="instructionChoose" class="text-center p-4 mb-3" style="background: #ffffff; border-radius: 14px; border: 1.5px dashed #cbd5e1; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #eff6ff; color: #2A8BF2; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto;">
                    <ion-icon name="finger-print-outline" style="font-size: 26px;"></ion-icon>
                </div>
                <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">Silakan Pilih Presensi</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                    Ketuk tombol <strong>Masuk Kerja</strong> atau <strong>Pulang Kerja</strong> di atas untuk membuka kamera &amp; lokasi presensi.
                </div>
            </div>

            <!-- Area Kamera dan Map (Hanya Muncul Setelah Memilih Masuk / Pulang) -->
            <div id="presensiContentArea" style="display: none;">
                <!-- 2. Camera View & Face Guide Overlay -->
                <input type="hidden" id="lokasi">
                <div class="camera-wrapper">
                    <div class="webcam-capture"></div>

                    @if ($scan_aktif)
                    <!-- Face Oval Guide Overlay (Sederhana Tanpa Badge) -->
                    <div class="face-guide-overlay">
                        <div class="face-oval" id="faceOval"></div>
                    </div>
                    @endif
                </div>

                @if ($scan_aktif)
                <!-- Keterangan Selesai Kedip & Senyum (Sederhana) -->
                <div class="d-flex justify-content-center gap-2 mt-2 mb-1">
                    <span id="pillBlink" class="badge bg-light border text-muted px-3 py-2" style="font-size: 12px; font-weight: 600; border-radius: 8px;">
                        Kedip Mata: <span id="badgeBlink">Belum</span>
                    </span>
                    <span id="pillSmile" class="badge bg-light border text-muted px-3 py-2" style="font-size: 12px; font-weight: 600; border-radius: 8px;">
                        Senyum: <span id="badgeSmile">Belum</span>
                    </span>
                </div>

                <!-- Fallback Button for Manual Shutter if Environment is Dark -->
                <div class="text-center mt-2 mb-2">
                    <button type="button" class="btn btn-link text-muted btn-sm p-0" id="btnManualShutter" style="font-size: 11.5px; text-decoration: none;">
                        Kendala sensor? Ambil foto manual
                    </button>
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

        // Liveness State
        var blinkVerified = false;
        var smileVerified = false;
        var hasEyeBeenOpen = false;
        var hasEyeBeenClosed = false;

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

        // Setup Webcam
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 85
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

        $("#btnPilihMasuk").click(function() {
            updateModeUI('in');
        });

        $("#btnPilihPulang").click(function() {
            updateModeUI('out');
        });

        // Reset Liveness State
        function resetLiveness() {
            if (!scanAktif) return;

            blinkVerified = false;
            smileVerified = false;
            hasEyeBeenOpen = false;
            hasEyeBeenClosed = false;

            $("#faceOval").removeClass('verified detected');
            $("#pillBlink").removeClass('bg-success text-white border-success').addClass('bg-light text-muted');
            $("#badgeBlink").text('Belum');
            $("#pillSmile").removeClass('bg-success text-white border-success').addClass('bg-light text-muted');
            $("#badgeSmile").text('Belum');
        }

        function setDirective(text, showSpinner) {
            // Sederhana tanpa badge melayang di atas kamera
        }

        // Eye Aspect Ratio (EAR) Calculation for Blink Detection
        function getEar(eye) {
            if (!eye || eye.length < 6) return 0.3;
            var d1 = Math.hypot(eye[1].x - eye[5].x, eye[1].y - eye[5].y);
            var d2 = Math.hypot(eye[2].x - eye[4].x, eye[2].y - eye[4].y);
            var dHoriz = Math.hypot(eye[0].x - eye[3].x, eye[0].y - eye[3].y);
            if (dHoriz === 0) return 0.3;
            return (d1 + d2) / (2.0 * dHoriz);
        }

        // Load Face API Models
        async function loadModels() {
            try {
                var modelUrl = "{{ asset('assets/models') }}";
                await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);
                await faceapi.nets.faceLandmark68Net.loadFromUri(modelUrl);
                await faceapi.nets.faceExpressionNet.loadFromUri(modelUrl);
                isModelLoaded = true;
            } catch (err) {
                console.warn("Gagal memuat model lokal face-api:", err);
                // Coba fallback CDN
                try {
                    var cdnUrl = "https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model";
                    await faceapi.nets.tinyFaceDetector.loadFromUri(cdnUrl);
                    await faceapi.nets.faceLandmark68Net.loadFromUri(cdnUrl);
                    await faceapi.nets.faceExpressionNet.loadFromUri(cdnUrl);
                    isModelLoaded = true;
                } catch (cdnErr) {
                    console.error("Gagal memuat model CDN:", cdnErr);
                }
            }
        }

        // Start Detection Loop
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
                var options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.45 });
                var detection = await faceapi.detectSingleFace(videoEl, options)
                    .withFaceLandmarks()
                    .withFaceExpressions();

                if (detection) {
                    $("#faceOval").addClass('detected');

                    // Check Blink
                    if (!blinkVerified) {
                        var leftEye = detection.landmarks.getLeftEye();
                        var rightEye = detection.landmarks.getRightEye();
                        var ear = (getEar(leftEye) + getEar(rightEye)) / 2.0;

                        if (ear > 0.25) {
                            hasEyeBeenOpen = true;
                        } else if (hasEyeBeenOpen && ear < 0.21) {
                            hasEyeBeenClosed = true;
                        }

                        if (hasEyeBeenClosed && ear > 0.24) {
                            blinkVerified = true;
                            $("#pillBlink").removeClass('bg-light text-muted').addClass('bg-success text-white border-success');
                            $("#badgeBlink").text('Selesai ✓');
                            playChime(620, 'sine', 0.15);
                        }
                    }

                    // Check Smile
                    if (!smileVerified) {
                        var happyScore = (detection.expressions && detection.expressions.happy) ? detection.expressions.happy : 0;
                        if (happyScore > 0.55) {
                            smileVerified = true;
                            $("#pillSmile").removeClass('bg-light text-muted').addClass('bg-success text-white border-success');
                            $("#badgeSmile").text('Selesai ✓');
                            playChime(850, 'sine', 0.20);
                        }
                    }

                    if (blinkVerified && smileVerified) {
                        // All Verified!
                        $("#faceOval").removeClass('detected').addClass('verified');
                        playChime(1050, 'triangle', 0.3);

                        isScanningActive = false;
                        setTimeout(function() {
                            executeAttendance(selectedStatus);
                        }, 500);
                        return;
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

            isSubmitting = true;

            var image = "";
            try {
                Webcam.snap(function(uri) {
                    image = uri;
                });
            } catch (err) {
                console.error(err);
            }

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

        // Manual Shutter Button Fallback
        $("#btnManualShutter").click(function(e) {
            e.preventDefault();

            if (!selectedStatus) {
                Swal.fire({
                    title: 'Pilih Jenis Presensi',
                    text: 'Silakan pilih jenis presensi terlebih dahulu sebelum mengambil foto:',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#dc2626',
                    confirmButtonText: 'Masuk Kerja',
                    cancelButtonText: 'Pulang Kerja'
                }).then((res) => {
                    if (res.isConfirmed) {
                        updateModeUI('in');
                    } else if (res.dismiss === Swal.DismissReason.cancel) {
                        updateModeUI('out');
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Presensi Manual',
                text: 'Ambil foto sekarang untuk Presensi ' + (selectedStatus === 'in' ? 'Masuk' : 'Pulang') + '?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: selectedStatus === 'in' ? '#16a34a' : '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Foto & Simpan',
                cancelButtonText: 'Batal'
            }).then((res) => {
                if (res.isConfirmed) {
                    executeAttendance(selectedStatus);
                }
            });
        });

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
            executeAttendance(selectedStatus);
        });

        // Initialize on Document Ready
        $(document).ready(function() {
            if (scanAktif) {
                loadModels().then(function() {
                    startFaceScanningLoop();
                });
            }
        });
    </script>
@endpush
