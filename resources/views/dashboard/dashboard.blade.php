@extends('layouts.presensi')
@section('content')
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 28px;
            text-decoration: none;
            right: 16px;
            top: 22px;
            z-index: 10;
        }

        .logout:hover {
            color: white;
        }

        #user-section {
            min-height: 190px !important;
            height: auto !important;
            padding: 24px 18px 55px 18px !important;
            position: relative;
            background-color: #2A8BF2;
            background: linear-gradient(135deg, #2A8BF2 0%, #1A73E8 100%);
        }

        #user-detail {
            display: flex;
            align-items: center;
            margin-top: 6px !important;
        }

        #user-detail .avatar {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        #user-detail .avatar img {
            width: 64px !important;
            height: 64px !important;
            border-radius: 50% !important;
            object-fit: cover !important;
            border: 3px solid #ffffff !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22), 0 0 0 2px rgba(255, 255, 255, 0.4) !important;
            background-color: #ffffff;
        }

        #user-info {
            margin-left: 14px !important;
            line-height: 1.3 !important;
            max-width: calc(100% - 110px);
        }

        #user-name {
            font-size: 15.5px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin-bottom: 3px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #user-role {
            font-size: 12.5px !important;
            color: rgba(255, 255, 255, 0.92) !important;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #menu-section {
            position: relative !important;
            top: 0 !important;
            margin-top: -42px !important;
            width: 100% !important;
            z-index: 99 !important;
            padding: 0 16px !important;
        }

        #presence-section {
            position: relative !important;
            top: 0 !important;
            width: 100% !important;
            margin-top: 10px !important;
            background-color: transparent !important;
        }

        .todaypresence {
            margin-top: 0 !important;
            margin-bottom: 16px !important;
        }

        /* Card Presensi Masuk & Pulang Simetris */
        .todaypresence .row {
            display: flex !important;
            align-items: stretch !important;
        }

        .todaypresence .col-6 {
            display: flex !important;
        }

        .todaypresence .card {
            width: 100% !important;
            border-radius: 16px !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
        }

        .gradasigreen {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
            color: #ffffff !important;
        }

        .gradasired {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%) !important;
            color: #ffffff !important;
        }

        .todaypresence .card-body {
            padding: 12px 14px !important;
            display: flex !important;
            align-items: center !important;
            width: 100% !important;
        }

        .todaypresence .presencecontent {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 12px !important;
            width: 100% !important;
        }

        .todaypresence .iconpresence {
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            min-height: 48px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.22) !important;
            backdrop-filter: blur(4px);
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            flex-shrink: 0 !important;
            border: 2px solid rgba(255, 255, 255, 0.6) !important;
            padding: 0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
        }

        .todaypresence .iconpresence img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            border-radius: 50% !important;
            display: block !important;
        }

        .todaypresence .iconpresence ion-icon {
            font-size: 26px !important;
            color: #ffffff !important;
            display: block !important;
        }

        .todaypresence .presencedetail {
            flex: 1 !important;
            min-width: 0 !important;
            line-height: normal !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
        }

        .todaypresence .presencetitle {
            font-size: 14px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin-bottom: 3px !important;
            line-height: 1.2 !important;
            text-transform: capitalize !important;
        }

        .todaypresence .presencedetail span {
            font-size: 12.5px !important;
            font-weight: 600 !important;
            color: rgba(255, 255, 255, 0.95) !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            display: block !important;
        }
    </style>
    @csrf

    <div class="section" id="user-section">
        <a href="/proseslogout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>

        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('karyawan')->user()->foto))
                    @php
                        $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded" style="width:64px; height:64px; object-fit:cover;"
                         onerror="this.src='{{ asset('assets/img/sample/avatar/avatar1.jpg') }}'">
                @else
                    <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w64 rounded" style="width:64px; height:64px; object-fit:cover;">
                @endif
            </div>
            <div id="user-info">
                <h3 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h3>
                <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/histori" class="warning" style="font-size: 40px;">
                                <ion-icon name="calendar-number"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Izin</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editlokasi" class="orange" style="font-size: 40px;">
                                <ion-icon name="location"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Lokasi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            @if ($dataliburhariini != null)
                <div class="card mb-2" style="background: #ffffff; border-radius: 10px; border-left: 4px solid #e11d48; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center">
                            <ion-icon name="calendar" style="font-size: 26px; color: #e11d48; margin-right: 12px; min-width: 26px;"></ion-icon>
                            <div style="line-height: 1.2;">
                                <div style="font-size: 0.7rem; font-weight: 700; color: #e11d48; text-transform: uppercase; letter-spacing: 0.5px;">HARI INI LIBUR</div>
                                <div style="font-size: 0.88rem; font-weight: 600; color: #1e293b; margin-top: 2px;">{{ $dataliburhariini->keterangan }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensihariini != null && !empty($presensihariini->foto_in))
                                        @php
                                            $path = Storage::url('uploads/absensi/' . $presensihariini->foto_in);
                                        @endphp
                                        <img src="{{ url($path) }}?v={{ time() }}" alt="Foto Masuk" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <ion-icon name="camera" style="display: none;"></ion-icon>
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensihariini != null && $presensihariini->jam_in != null ? $presensihariini->jam_in : ($dataliburhariini != null ? 'Libur' : 'Belum Absen') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensihariini != null && !empty($presensihariini->foto_out))
                                        @php
                                            $path = Storage::url('uploads/absensi/' . $presensihariini->foto_out);
                                        @endphp
                                        <img src="{{ url($path) }}?v={{ time() }}" alt="Foto Pulang" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <ion-icon name="camera" style="display: none;"></ion-icon>
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : ($dataliburhariini != null ? 'Libur' : 'Belum Absen') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="rekappresensi">
            <h3>Rekap Aktivitas Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 8px !important; line-height:0.8rem">
                            <span class="badge bg-success"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir ?? 0 }}</span>
                            <ion-icon name="accessibility-outline" style="font-size: 1.6rem;"
                                class="text-success mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.75rem; font-weight:500">Hadir</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 8px !important; line-height:0.8rem">
                            <span class="badge bg-warning"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">
                                {{ ($rekappresensi->jmlizin ?? 0) + ($rekappresensi->jmlsakit ?? 0) }}
                            </span>
                            <ion-icon name="newspaper-outline" style="font-size: 1.6rem;"
                                class="text-warning mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.75rem; font-weight:500">Izin/Sakit</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 8px !important; line-height:0.8rem">
                            <span class="badge bg-info"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">
                                {{ $rekappresensi->jmlcuti ?? 0 }}
                            </span>
                            <ion-icon name="calendar-outline" style="font-size: 1.6rem;"
                                class="text-info mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.75rem; font-weight:500">Cuti</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 8px !important; line-height:0.8rem">
                            <span class="badge bg-danger"
                                style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">
                                {{ $rekappresensi->jmlterlambat ?? 0 }}
                            </span>
                            <ion-icon name="alarm-outline" style="font-size: 1.6rem;"
                                class="text-danger mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.75rem; font-weight:500">Telat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            Minggu Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Leaderboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:20px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    @if ($historimingguini->isEmpty())
                        <div class="card" style="border-radius: 12px; border: 1px dashed #cbd5e1;">
                            <div class="card-body text-center py-4">
                                <ion-icon name="calendar-outline" style="font-size: 36px; color: #94a3b8;"></ion-icon>
                                <p class="text-muted mt-2 mb-0" style="font-size: 13px;">Belum ada catatan aktivitas presensi pada minggu ini.</p>
                            </div>
                        </div>
                    @endif

                    @foreach ($historimingguini as $d)
                        @if ($d->status == 'h')
                            @php
                                $has_jam_in = !empty($d->jam_in);
                                $has_jam_out = !empty($d->jam_out);
                                $jam_masuk_jadwal = !empty($d->jam_masuk) ? date('H:i', strtotime($d->jam_masuk)) : '08:00';
                                $jam_in_formatted = $has_jam_in ? date('H:i', strtotime($d->jam_in)) : null;

                                $jadwal_jam_masuk = $d->tgl_presensi . ' ' . $jam_masuk_jadwal;
                                $jam_presensi = $d->tgl_presensi . ' ' . ($jam_in_formatted ?? '00:00');

                                $is_tidak_absen_masuk = false;
                                $is_terlambat = false;

                                if (!$has_jam_in) {
                                    $is_tidak_absen_masuk = true;
                                    $is_terlambat = true;
                                } elseif ($jam_in_formatted > $jam_masuk_jadwal) {
                                    $is_terlambat = true;
                                }

                                $is_pulang_awal = $has_jam_out && (!empty($d->jam_pulang) && date('H:i', strtotime($d->jam_out)) < date('H:i', strtotime($d->jam_pulang)));
                            @endphp
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid {{ $is_tidak_absen_masuk ? '#fecaca' : ($is_terlambat ? '#fed7aa' : '#bbf7d0') }}; border-left: 5px solid {{ $is_tidak_absen_masuk ? '#ef4444' : ($is_terlambat ? '#f97316' : '#22c55e') }}; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="finger-print-outline" style="font-size: 42px;"
                                                class="{{ $is_tidak_absen_masuk ? 'text-danger' : ($is_terlambat ? 'text-warning' : 'text-success') }}"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px; {{ $is_tidak_absen_masuk ? 'background:#fee2e2; color:#b91c1c;' : ($is_terlambat ? 'background:#ffedd5; color:#c2410c;' : 'background:#dcfce7; color:#15803d;') }}">
                                                    {{ $is_tidak_absen_masuk ? 'Tidak Absen Pagi' : ($is_terlambat ? 'Terlambat' : 'Hadir') }}
                                                </span>
                                            </div>
                                            <div style="font-size: 12px; color: #64748b; margin-bottom: 3px;">
                                                <span style="font-weight: 600; color: #2A8BF2;">{{ $d->nama_jam_kerja ?? 'Reguler' }}</span>
                                                <span class="mx-1">•</span>
                                                <span>Shift: {{ date('H:i', strtotime($d->jam_masuk ?? '08:00')) }} - {{ date('H:i', strtotime($d->jam_pulang ?? '16:00')) }}</span>
                                            </div>
                                            <div style="font-size: 13px; margin-bottom: 4px;">
                                                <span>Scan: </span>
                                                {!! $has_jam_in ? '<strong style="color:#0f172a;">' . date('H:i', strtotime($d->jam_in)) . '</strong>' : '<span class="text-danger font-weight-bold">Belum Scan</span>' !!}
                                                <span> - </span>
                                                {!! $has_jam_out ? '<strong style="color:#0f172a;">' . date('H:i', strtotime($d->jam_out)) . '</strong>' : '<span class="text-danger font-weight-bold">Belum Scan</span>' !!}
                                                @if ($is_pulang_awal)
                                                    <span class="text-warning ml-1" style="font-size: 11px; font-weight: 600;">(Pulang Awal)</span>
                                                @endif
                                            </div>
                                            <div>
                                                @if ($is_tidak_absen_masuk)
                                                    <span class="text-danger font-weight-bold" style="font-size: 12px;">
                                                        <ion-icon name="alert-circle" style="vertical-align: -1px;"></ion-icon> Terlambat (Tidak Absen Pagi)
                                                    </span>
                                                @elseif ($is_terlambat)
                                                    @php
                                                        $jmlterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
                                                        $jmlterlambatdesimal = hitungjamterlambatdesimal($jadwal_jam_masuk, $jam_presensi);
                                                    @endphp
                                                    <span class="text-danger font-weight-bold" style="font-size: 12px;">
                                                        <ion-icon name="alert-circle" style="vertical-align: -1px;"></ion-icon> Terlambat {{ $jmlterlambat }} ({{ $jmlterlambatdesimal }} Jam)
                                                    </span>
                                                @else
                                                    <span class="text-success font-weight-bold" style="font-size: 12px;">
                                                        <ion-icon name="checkmark-circle" style="vertical-align: -1px;"></ion-icon> Tepat Waktu
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($d->status == 'tl')
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid #cffafe; border-left: 5px solid #06b6d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="briefcase-outline" style="font-size: 42px;" class="text-info"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="background:#cffafe; color:#0e7490; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                                    Tugas Luar
                                                </span>
                                            </div>
                                            <h3 style="line-height: 1.2; font-size: 13px; font-weight: 600; color: #0284c7; margin-bottom: 2px;">
                                                TUGAS LUAR {{ !empty($d->kode_izin) ? '- ' . $d->kode_izin : '' }}
                                            </h3>
                                            <span style="font-size: 12px; color: #475569;">
                                                {{ $d->keterangan }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($d->status == 'i')
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid #fef3c7; border-left: 5px solid #f59e0b; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="document-text-outline" style="font-size: 42px;" class="text-warning"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="background:#fef3c7; color:#b45309; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                                    Izin
                                                </span>
                                            </div>
                                            <h3 style="line-height: 1.2; font-size: 13px; font-weight: 600; color: #d97706; margin-bottom: 2px;">
                                                IZIN {{ !empty($d->kode_izin) ? '- ' . $d->kode_izin : '' }}
                                            </h3>
                                            <span style="font-size: 12px; color: #475569;">
                                                {{ $d->keterangan }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($d->status == 'l')
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid #fee2e2; border-left: 5px solid #ef4444; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="calendar-outline" style="font-size: 42px;" class="text-danger"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="background:#fee2e2; color:#b91c1c; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                                    Libur
                                                </span>
                                            </div>
                                            <h3 style="line-height: 1.2; font-size: 13px; font-weight: 600; color: #dc2626; margin-bottom: 2px;">
                                                LIBUR NASIONAL / BERSAMA
                                            </h3>
                                            <span class="text-danger font-weight-bold" style="font-size: 12px;">
                                                {{ !empty($d->keterangan) ? $d->keterangan : 'Libur Nasional / Bersama' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($d->status == 's')
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid #bfdbfe; border-left: 5px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="medkit-outline" style="font-size: 42px;" class="text-primary"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="background:#dbeafe; color:#1d4ed8; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                                    Sakit
                                                </span>
                                            </div>
                                            <h3 style="line-height: 1.2; font-size: 13px; font-weight: 600; color: #2563eb; margin-bottom: 2px;">
                                                SAKIT {{ !empty($d->kode_izin) ? '- ' . $d->kode_izin : '' }}
                                            </h3>
                                            <span style="font-size: 12px; color: #475569;">
                                                {{ $d->keterangan }}
                                            </span>
                                            @if (!empty($d->doc_sid))
                                                <div class="mt-1">
                                                    <a href="{{ asset('storage/uploads/sid/' . $d->doc_sid) }}" target="_blank" class="text-primary font-weight-bold" style="font-size: 12px;">
                                                        <ion-icon name="document-attach-outline"></ion-icon> Lihat Surat Dokter (SID)
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($d->status == 'c')
                            <div class="card mb-2" style="border-radius: 12px; border: 1px solid #ddd6fe; border-left: 5px solid #8b5cf6; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <div class="card-body py-2 px-3">
                                    <div class="historicontent align-items-center">
                                        <div class="iconpresensi mr-2" style="min-width: 44px;">
                                            <ion-icon name="calendar-number-outline" style="font-size: 42px; color: #8b5cf6;"></ion-icon>
                                        </div>
                                        <div class="datapresensi flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h4 style="margin:0px !important; font-weight: 700; color: #1e293b; font-size: 14px;">
                                                    {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                                </h4>
                                                <span class="badge" style="background:#ede9fe; color:#6d28d9; font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 6px;">
                                                    Cuti
                                                </span>
                                            </div>
                                            <h3 style="line-height: 1.2; font-size: 13px; font-weight: 600; color: #7c3aed; margin-bottom: 2px;">
                                                CUTI : {{ $d->nama_cuti ?? $d->kode_izin }}
                                            </h3>
                                            <span style="font-size: 12px; color: #475569;">
                                                {{ $d->keterangan }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <ul class="listview image-listview">
                        @foreach ($leaderboard as $d)
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                            <b>{{ $d->nama_lengkap }}</b><br>
                                            <small class="text-muted">{{ $d->jabatan }}</small>
                                        </div>
                                        <span class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $d->jam_in }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>

            </div>

            <!-- Footer Copyright Balai Pelatihan Koperasi dan Usaha Kecil Prov. Kalsel -->
            <div class="text-center pt-2 pb-4" style="color: #64748b; font-size: 11.5px; line-height: 1.5; margin-bottom: 75px;">
                <div style="font-weight: 600; color: #475569;">
                    Copyright &copy; {{ date('Y') }} Balai Pelatihan Koperasi dan Usaha Kecil Prov. Kalsel
                </div>
                <div style="color: #94a3b8; font-size: 11px;">
                    All Rights Reserved
                </div>
            </div>
        </div>
    </div>
@endsection
