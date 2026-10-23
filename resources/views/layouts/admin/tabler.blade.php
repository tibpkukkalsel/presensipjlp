<!doctype html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>PRESIS - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('Logo Presis.png') }}" sizes="32x32"
        onerror="this.src='{{ asset('assets/img/favicon.png') }}'">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Logo Presis.png') }}">

    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Source+Sans+3:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- Extra Plugins: Datepicker & Leaflet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin="" />

    <!-- AdminLTE Custom & Compatibility Bridge -->
    <style>
        body {
            font-family: 'Poppins', 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Compatibility Bridge for view components */
        .page-header {
            margin-bottom: 1.25rem;
        }

        .page-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .page-pretitle {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .page-body {
            margin-bottom: 1.5rem;
        }

        .avatar {
            position: relative;
            width: 2.75rem;
            height: 2.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #ffffff;
            font-weight: 600;
            flex-shrink: 0;
        }

        .avatar.bg-success {
            background-color: #10b981 !important;
        }

        .avatar.bg-info {
            background-color: #0284c7 !important;
        }

        .avatar.bg-warning {
            background-color: #f59e0b !important;
        }

        .avatar.bg-danger {
            background-color: #ef4444 !important;
        }

        .avatar.bg-primary {
            background-color: #2563eb !important;
        }

        .icon {
            width: 1.25rem;
            height: 1.25rem;
            stroke-width: 2;
            vertical-align: middle;
        }

        .app-main {
            background-color: #f8fafc;
            min-height: calc(100vh - 115px);
        }

        /* AdminLTE treeview active styling */
        .sidebar-menu .nav-link.active {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }

        .sidebar-menu .nav-treeview .nav-link.active {
            background-color: rgba(13, 110, 253, 0.25) !important;
            color: #38bdf8 !important;
            font-weight: 600;
        }

        /* =========================================================
           Input Icon & Form Control Bridge (Side-by-side alignment)
           Applies to all forms across the Admin Panel
           ========================================================= */
        .input-icon {
            position: relative;
            width: 100%;
            display: block;
        }

        .input-icon .input-icon-addon {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            color: #64748b;
            pointer-events: none;
            font-size: 1.15rem;
            z-index: 4;
            transition: color 0.15s ease-in-out;
        }

        .input-icon-addon .icon,
        .input-icon-addon svg,
        .input-icon-addon i,
        .input-icon-addon .bi {
            width: 1.25rem !important;
            height: 1.25rem !important;
            stroke-width: 1.75;
            color: inherit;
        }

        /* Default: icon on the left (addon before input) */
        .input-icon .form-control,
        .input-icon .form-select {
            padding-left: 2.75rem;
        }

        /* Icon on the right if addon is last-child */
        .input-icon .input-icon-addon:last-child {
            left: auto;
            right: 0;
        }

        .input-icon:has(.input-icon-addon:last-child) .form-control,
        .input-icon:has(.input-icon-addon:last-child) .form-select,
        .input-icon .form-control:not(:last-child),
        .input-icon .form-select:not(:last-child) {
            padding-left: 0.75rem;
            padding-right: 2.75rem;
        }

        /* Dual addons (left & right) */
        .input-icon:has(.input-icon-addon:first-child):has(.input-icon-addon:last-child) .form-control,
        .input-icon:has(.input-icon-addon:first-child):has(.input-icon-addon:last-child) .form-select {
            padding-left: 2.75rem;
            padding-right: 2.75rem;
        }

        /* Focus state: glow icon with primary color */
        .input-icon:focus-within .input-icon-addon {
            color: #0d6efd;
        }

        .input-icon-addon a,
        .input-icon-addon button,
        .input-icon-addon.clickable {
            pointer-events: auto;
            cursor: pointer;
        }

        /* Button & Icon Alignment */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
        }

        .btn .icon,
        .btn svg {
            width: 1.15rem;
            height: 1.15rem;
            stroke-width: 2;
            vertical-align: middle;
        }

        /* Modal blur & clean styling */
        .modal-blur {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* =========================================================
           Admin Tables & Action Buttons Styling (Clean, Centered, No Offside)
           ========================================================= */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            margin-bottom: 1rem;
        }

        .table {
            margin-bottom: 0 !important;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f8fafc !important;
            color: #334155 !important;
            font-size: 0.8rem !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.75rem 0.85rem !important;
            border-bottom: 2px solid #e2e8f0 !important;
            white-space: nowrap;
            vertical-align: middle !important;
        }

        .table tbody td {
            padding: 0.7rem 0.85rem !important;
            vertical-align: middle !important;
            font-size: 0.875rem;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover td {
            background-color: #f8fafc;
        }

        .table td form {
            display: inline-block;
            margin: 0 !important;
            vertical-align: middle;
        }

        /* Table Action Buttons Container */
        .table-actions,
        .table-action-btns,
        .table td .d-flex {
            display: inline-flex !important;
            align-items: center;
            gap: 0.35rem !important;
            white-space: nowrap;
        }

        /* Uniform Square Action Buttons */
        .btn-action {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            min-height: 32px !important;
            max-width: 32px !important;
            max-height: 32px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 6px !important;
            transition: all 0.15s ease-in-out;
            border: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            flex-shrink: 0;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12);
        }

        .btn-action svg,
        .btn-action .icon {
            width: 16px !important;
            height: 16px !important;
            stroke-width: 2 !important;
            margin: 0 !important;
        }

        /* Compact Table Badges */
        .table .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            gap: 0.25rem;
        }

        .table .badge svg,
        .table .badge .icon {
            width: 14px !important;
            height: 14px !important;
            margin: 0 !important;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        @include('layouts.admin.header')
        <!--end::Header-->

        <!--begin::Sidebar-->
        @include('layouts.admin.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">
            <div class="app-content pt-3 pb-4">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>
        <!--end::App Main-->

        <!--begin::Footer-->
        @include('layouts.admin.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Scripts-->
    <!-- jQuery (Needed for application scripts and plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <!-- OverlayScrollbars -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>

    <!-- Popper & Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- AdminLTE JS -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <!-- Plugin Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <script src="{{ asset('assets/js/lib/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.mask.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>

    <!-- AdminLTE OverlayScrollbars Init -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: 'os-theme-light',
                        autoHide: 'leave',
                        clickScroll: true,
                    },
                });
            }
        });
    </script>

    @stack('myscript')
    <!--end::Scripts-->
</body>

</html>
