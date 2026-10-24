<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#2A8BF2">
    <title>PRESIS BPKUK Prov. Kalsel</title>
    <meta name="description" content="PRESIS BPKUK Prov. Kalsel">
    <meta name="keywords" content="ABSEN PRESENSI BPKUK PROV. KALSEL" />
    <link rel="icon" type="image/png" href="{{ asset('Logo Presis.png') }}" sizes="32x32"
        onerror="this.src='{{ asset('assets/img/favicon.png') }}'">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Logo Presis.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <style>
        .selectmaterialize {
            display: block !important;
            background-color: transparent !important;
            border: 0px !important;
            border-bottom: 1px solid #9e9e9e !important;
            border-radius: 0 !important;
            outline: none !important;
            height: 3rem !important;
            width: 100% !important;
            font-size: 16px !important;
            font-family: inherit !important;
            margin: 0 0 8px 0 !important;
            padding: 0 28px 0 0 !important;
            color: #495057 !important;
            box-shadow: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 4px center !important;
            background-size: 20px 20px !important;
            cursor: pointer;
            box-sizing: content-box !important;
            transition: border-bottom 0.2s ease, box-shadow 0.2s ease;
        }

        .selectmaterialize:focus {
            border-bottom: 1px solid #2A8BF2 !important;
            box-shadow: 0 1px 0 0 #2A8BF2 !important;
            outline: none !important;
        }

        .selectmaterialize option {
            background-color: #ffffff;
            color: #1e293b;
            padding: 10px 14px;
            font-size: 15px;
        }

        /* Harmonize Materialize input focus with signature blue #2A8BF2 */
        input:not([type]):focus:not([readonly]),
        input[type=text]:not(.browser-default):focus:not([readonly]),
        input[type=password]:not(.browser-default):focus:not([readonly]),
        input[type=email]:not(.browser-default):focus:not([readonly]),
        textarea.materialize-textarea:focus:not([readonly]),
        .form-control.datepicker:focus {
            border-bottom: 1px solid #2A8BF2 !important;
            box-shadow: 0 1px 0 0 #2A8BF2 !important;
        }
    </style>

    <style>
        .historicontent {
            display: flex;
            margin-top: 10px;
        }

        .datapresensi {
            margin-left: 10px;
        }

        /* ========================================================
           GLOBAL APK & MOBILE WEBVIEW PRECISION FIX
           ======================================================== */
        .appHeader {
            height: 56px !important;
            min-height: 56px !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 9999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* Berikan padding atas otomatis agar konten tidak tertutup header */
        .has-app-header #appCapsule,
        body:has(.appHeader) #appCapsule {
            padding-top: 65px !important;
        }

        /* Berikan padding bawah aman agar tidak tertutup Bottom Navigation Bar */
        #appCapsule {
            padding-bottom: 95px !important;
            min-height: 100vh;
            box-sizing: border-box;
        }
    </style>
</head>

<body class="@hasSection('header') has-app-header @endif" style="background-color:#e9ecef;">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->

    @yield('header')

    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
    </div>
    <!-- * App Capsule -->


    @include('layouts.bottomNav')


    @include('layouts.script')



</body>

</html>
