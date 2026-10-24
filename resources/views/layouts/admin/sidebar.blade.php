<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/panel-adm14/dashboardadmin" class="brand-link text-decoration-none d-flex align-items-center gap-2 px-3">
            <!--begin::Brand Image-->
            <img src="{{ asset('Logo Presis.png') }}" alt="PRESIS Logo" class="brand-image opacity-75 shadow-sm rounded"
                style="max-height: 32px; width: auto;"
                onerror="this.src='{{ asset('assets/img/Logo Provinsi Kalimantan Selatan.png') }}'">
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-semibold text-white fs-6">PRESIS BPKUK</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false"
                id="navigation">

                <!-- 1. Home / Dashboard -->
                <li class="nav-header">Home</li>
                <li class="nav-item">
                    <a href="/panel-adm14/dashboardadmin"
                        class="nav-link {{ request()->is('panel-adm14/dashboardadmin') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- 2. Master -->
                <li class="nav-header">Master</li>
                @role('administrator', 'user')
                    <li class="nav-item">
                        <a href="/panel-adm14/seksi"
                            class="nav-link {{ request()->is(['panel-adm14/seksi', 'panel-adm14/departemen', 'panel-adm14/seksi/*', 'panel-adm14/departemen/*']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-diagram-3-fill"></i>
                            <p>Seksi</p>
                        </a>
                    </li>
                @endrole
                @role('administrator|admin departemen', 'user')
                    <li class="nav-item">
                        <a href="/panel-adm14/pegawai"
                            class="nav-link {{ request()->is(['panel-adm14/pegawai', 'panel-adm14/karyawan', 'panel-adm14/pegawai/*', 'panel-adm14/karyawan/*']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Pegawai</p>
                        </a>
                    </li>
                @endrole
                @role('administrator', 'user')
                    <li class="nav-item">
                        <a href="/panel-adm14/cabang" class="nav-link {{ request()->is(['panel-adm14/cabang', 'panel-adm14/cabang/*']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-geo-alt-fill"></i>
                            <p>Lokasi</p>
                        </a>
                    </li>
                @endrole
                <!-- 7. Konfigurasi (Dropdown - Administrator Only) -->
                @role('administrator', 'user')
                    <li class="nav-item {{ request()->is(['panel-adm14/konfigurasi', 'panel-adm14/konfigurasi/*']) ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is(['panel-adm14/konfigurasi', 'panel-adm14/konfigurasi/*']) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-gear-fill"></i>
                            <p>
                                Konfigurasi
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/harilibur"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/harilibur*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Hari Libur</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/jamkerja"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/jamkerja') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Jam Kerja</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/jamkerjadept"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/jamkerjadept*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Jam Kerja Kantor</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/users"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/users*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/penandatangan"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/penandatangan*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Pejabat Laporan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/panel-adm14/konfigurasi/scan"
                                    class="nav-link {{ request()->is('panel-adm14/konfigurasi/scan*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-camera"></i>
                                    <p>Scan Wajah</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <li class="nav-header">App</li>
                <!-- 3. Monitoring Presensi -->
                <li class="nav-item">
                    <a href="/panel-adm14/presensi/monitoring"
                        class="nav-link {{ request()->is('panel-adm14/presensi/monitoring') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-display"></i>
                        <p>Monitoring Presensi</p>
                    </a>
                </li>

                <!-- 4. Status Kehadiran Pegawai (Persetujuan Izin / Sakit / Cuti) -->
                <li class="nav-item">
                    <a href="/panel-adm14/presensi/izinsakit"
                        class="nav-link {{ request()->is(['panel-adm14/presensi/izinsakit*', 'panel-adm14/presensi/statuskehadiran*']) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-check-fill"></i>
                        <p>Status Kehadiran</p>
                    </a>
                </li>

                <li class="nav-header">Output</li>
                <!-- 6. Laporan (Dropdown) -->
                <li
                    class="nav-item {{ request()->is(['panel-adm14/presensi/laporan', 'panel-adm14/presensi/rekap']) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is(['panel-adm14/presensi/laporan', 'panel-adm14/presensi/rekap']) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>
                            Laporan
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/panel-adm14/presensi/laporan"
                                class="nav-link {{ request()->is('panel-adm14/presensi/laporan') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/panel-adm14/presensi/rekap"
                                class="nav-link {{ request()->is('panel-adm14/presensi/rekap') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Rekap Presensi</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
