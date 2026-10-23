<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body shadow-sm">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <span class="nav-link fw-semibold text-secondary">
                    Sistem Presensi &amp; Pelaporan Pegawai BPKUK Prov. Kalsel
                </span>
            </li>
        </ul>
        <!--end::Start Navbar Links-->

        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto align-items-center">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item d-none d-sm-block">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen" title="Layar Penuh">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <img src="{{ asset('tabler/static/avatars/000m.jpg') }}" class="user-image rounded-circle shadow-sm"
                        alt="User Avatar" style="width: 32px; height: 32px; object-fit: cover;"
                        onerror="this.src='{{ asset('assets/img/user.png') }}'">
                    <span class="d-none d-md-inline fw-medium text-dark">
                        {{ Auth::guard('user')->user()->name }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-sm border-0">
                    <!--begin::User Header-->
                    <li class="user-header text-bg-primary text-center p-3 rounded-top">
                        <img src="{{ asset('tabler/static/avatars/000m.jpg') }}" class="rounded-circle shadow mb-2"
                            alt="User Avatar" style="width: 60px; height: 60px; object-fit: cover;"
                            onerror="this.src='{{ asset('assets/img/user.png') }}'">
                        <p class="mb-0 fw-bold fs-6">
                            {{ Auth::guard('user')->user()->name }}
                        </p>
                        <small class="badge bg-light text-primary mt-1">
                            {{ ucwords(Auth::guard('user')->user()->roles->pluck('name')[0] ?? 'Admin') }}
                        </small>
                    </li>
                    <!--end::User Header-->

                    <!--begin::Menu Footer-->
                    <li class="user-footer p-2 d-flex justify-content-between align-items-center bg-light rounded-bottom">
                        <a href="/panel-adm14/konfigurasi/users" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-person me-1"></i> Profil
                        </a>
                        <a href="/panel-adm14/proseslogoutadmin" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->
