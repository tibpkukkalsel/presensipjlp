@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Riwayat Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <style>
        body {
            background-color: #f8fafc !important;
            font-family: "Inter", sans-serif !important;
        }

        body, button, input, select, textarea, .pageTitle, .cal-month-title, .detail-card-title, .weekday-name, .day-circle {
            font-family: "Inter", sans-serif !important;
        }

        #appCapsule {
            padding-top: 60px !important;
            padding-bottom: 90px !important;
        }

        .calendar-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 16px 14px 12px 14px;
            margin: 12px 14px 16px 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
        }

        .cal-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            padding: 0 4px;
        }

        .cal-nav-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .cal-nav-btn {
            background: none;
            border: none;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .cal-nav-btn:active {
            background-color: #f1f5f9;
        }

        .cal-month-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.2px;
        }

        /* Days Grid Header */
        .weekdays-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            margin-bottom: 8px;
        }

        .weekday-name {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            padding: 4px 0;
        }

        .weekday-name.weekend {
            color: #ef4444;
        }

        /* Dates Grid */
        .dates-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            row-gap: 6px;
        }

        .day-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            cursor: pointer;
            position: relative;
            user-select: none;
            border-radius: 12px;
            transition: all 0.15s ease-in-out;
        }

        .day-cell:active {
            transform: scale(0.94);
        }

        .day-circle {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14.5px;
            font-weight: 600;
            color: #0f172a;
            border-radius: 50%;
            position: relative;
            transition: all 0.2s;
        }

        .day-cell.other-month .day-circle {
            color: #cbd5e1;
            font-weight: 400;
        }

        .day-cell.weekend .day-circle {
            color: #ef4444;
        }

        .day-cell.other-month.weekend .day-circle {
            color: #fca5a5;
        }

        /* Selected Day (Signature Blue) */
        .day-cell.selected .day-circle {
            background: linear-gradient(135deg, #1e74fd 0%, #2A8BF2 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(42, 139, 242, 0.4);
            font-weight: 700;
        }

        /* Today indicator (Yellow Golden Circle if not selected) */
        .day-cell.is-today:not(.selected) .day-circle {
            background: #f59e0b !important;
            color: #ffffff !important;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.35);
            font-weight: 700;
        }

        /* Indicators below date */
        .day-indicator-wrapper {
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }

        .check-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 15px;
            height: 15px;
            background-color: #10b981;
            color: #ffffff;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 900;
            box-shadow: 0 1px 4px rgba(16, 185, 129, 0.3);
        }

        .badge-subtle {
            font-size: 9px;
            font-weight: 700;
            border-radius: 3px;
            padding: 1px 3px;
            line-height: 1;
            color: #ffffff;
        }

        .badge-izin { background-color: #f59e0b; }
        .badge-sakit { background-color: #06b6d4; }
        .badge-cuti { background-color: #8b5cf6; }
        .badge-tl { background-color: #059669; }
        .badge-libur { background-color: #10b981; }

        .dot-past {
            width: 4.5px;
            height: 4.5px;
            background-color: #334155;
            border-radius: 50%;
        }

        /* Detail Cards Below Calendar */
        .detail-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 18px 20px;
            margin: 0 14px 14px 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            transition: all 0.25s ease;
        }

        .detail-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .detail-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.3;
        }

        .detail-card-date {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            margin-top: 3px;
        }

        .status-pill {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.2px;
            text-transform: capitalize;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pill-hadir {
            background: linear-gradient(135deg, #ff7700 0%, #ff8c00 100%);
        }

        .pill-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .pill-blue {
            background: linear-gradient(135deg, #1e74fd 0%, #2A8BF2 100%);
        }

        .pill-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .pill-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .pill-secondary {
            background: #64748b;
        }

        .detail-card-body {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 14px;
        }

        .detail-thumb {
            width: 72px;
            height: 72px;
            border-radius: 14px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            background-color: #f8fafc;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .detail-thumb-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 14px;
            background-color: #f1f5f9;
            border: 2px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 28px;
            flex-shrink: 0;
        }

        .time-display {
            font-size: 28px;
            font-weight: 800;
            color: #f59e0b;
            letter-spacing: -0.5px;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .time-display.blue-time {
            color: #2A8BF2;
        }

        .time-subtitle {
            font-size: 12.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 4px;
        }

        .status-badge-inline {
            font-size: 11.5px;
            font-weight: 600;
            margin-top: 4px;
            display: inline-block;
        }

        /* Empty State Card */
        .empty-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px 20px;
            margin: 0 14px 14px 14px;
            text-align: center;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
        }

        .empty-icon {
            font-size: 44px;
            color: #2A8BF2;
            margin-bottom: 8px;
        }

        .empty-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .empty-desc {
            font-size: 13px;
            color: #64748b;
            max-width: 280px;
            margin: 0 auto;
        }

    </style>

    <!-- Calendar Card Container -->
    <div class="calendar-card">
        <!-- Calendar Header Navigation -->
        <div class="cal-header">
            <div class="cal-nav-group">
                <button type="button" class="cal-nav-btn" id="btnPrevMonth">
                    <ion-icon name="chevron-back-outline" style="font-size: 20px;"></ion-icon>
                </button>
                <div class="cal-month-title" id="txtMonthYear">
                    {{ $namabulan[(int)$bulan] }} {{ $tahun }}
                </div>
                <button type="button" class="cal-nav-btn" id="btnNextMonth">
                    <ion-icon name="chevron-forward-outline" style="font-size: 20px;"></ion-icon>
                </button>
            </div>
        </div>

        <!-- Days of Week Header (Sen, Sel, Rab, Kam, Jum, Sab, Min) -->
        <div class="weekdays-grid">
            <div class="weekday-name">Sen</div>
            <div class="weekday-name">Sel</div>
            <div class="weekday-name">Rab</div>
            <div class="weekday-name">Kam</div>
            <div class="weekday-name">Jum</div>
            <div class="weekday-name weekend">Sab</div>
            <div class="weekday-name weekend">Min</div>
        </div>

        <!-- Dates Grid -->
        <div class="dates-grid" id="datesGrid">
            <!-- Dynamically populated by JavaScript -->
        </div>
    </div>

    <!-- Attendance Details Container (Updates when day is tapped) -->
    <div id="attendanceDetailSection">
        <!-- Populated dynamically via JS -->
    </div>
@endsection

@push('myscript')
<script>
    // Initial server data
    const monthNames = [
        "", "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    let currentMonth = parseInt("{{ $bulan }}");
    let currentYear = parseInt("{{ $tahun }}");

    // Today's date components
    const now = new Date();
    const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
    
    // Selected date starts at today if in current month/year, else 1st of month
    let selectedDate = todayStr;

    // Presensi records keyed by 'YYYY-MM-DD'
    let presensiMap = {};
    let defaultSchedule = @json($defaultJamKerja ?? null);

    // Initial server data injection
    const initialHistori = @json($histori ?? []);
    populatePresensiMap(initialHistori);

    function populatePresensiMap(data) {
        presensiMap = {};
        if (Array.isArray(data)) {
            data.forEach(item => {
                if (item && item.tgl_presensi) {
                    presensiMap[item.tgl_presensi] = item;
                }
            });
        }
    }

    // Format Date to "Selasa, 20 Oktober 2026"
    function formatIndoFullDate(dateStr) {
        const parts = dateStr.split('-');
        const y = parseInt(parts[0]);
        const m = parseInt(parts[1]);
        const d = parseInt(parts[2]);
        const dateObj = new Date(y, m - 1, d);
        const dayName = dayNames[dateObj.getDay()];
        const monthName = monthNames[m];
        return `${dayName}, ${d} ${monthName} ${y}`;
    }

    function renderCalendar() {
        const grid = document.getElementById('datesGrid');
        grid.innerHTML = '';

        // Update Month Title
        document.getElementById('txtMonthYear').innerText = `${monthNames[currentMonth]} ${currentYear}`;

        // First day of current month
        const firstDay = new Date(currentYear, currentMonth - 1, 1);
        // Day of week: 0=Sun, 1=Mon, ..., 6=Sat
        // Convert to ISO: Mon=1, Tue=2, ..., Sun=7
        let startDayISO = firstDay.getDay() === 0 ? 7 : firstDay.getDay();

        // Days in current month
        const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

        // Days in previous month
        const daysInPrevMonth = new Date(currentYear, currentMonth - 1, 0).getDate();

        const allCells = [];

        // 1. Previous month trailing days
        const prevPad = startDayISO - 1;
        for (let i = prevPad; i > 0; i--) {
            const dayNum = daysInPrevMonth - i + 1;
            const prevMonthDate = new Date(currentYear, currentMonth - 2, dayNum);
            const y = prevMonthDate.getFullYear();
            const m = String(prevMonthDate.getMonth() + 1).padStart(2, '0');
            const d = String(dayNum).padStart(2, '0');
            const dateStr = `${y}-${m}-${d}`;
            const isWeekend = (prevMonthDate.getDay() === 0 || prevMonthDate.getDay() === 6);

            allCells.push({
                dayNum: dayNum,
                dateStr: dateStr,
                isOtherMonth: true,
                isWeekend: isWeekend,
                data: presensiMap[dateStr] || null
            });
        }

        // 2. Current month days
        for (let dayNum = 1; dayNum <= daysInMonth; dayNum++) {
            const curDate = new Date(currentYear, currentMonth - 1, dayNum);
            const m = String(currentMonth).padStart(2, '0');
            const d = String(dayNum).padStart(2, '0');
            const dateStr = `${currentYear}-${m}-${d}`;
            const isWeekend = (curDate.getDay() === 0 || curDate.getDay() === 6);

            allCells.push({
                dayNum: dayNum,
                dateStr: dateStr,
                isOtherMonth: false,
                isWeekend: isWeekend,
                data: presensiMap[dateStr] || null
            });
        }

        // 3. Next month leading days (to complete the 7-column rows)
        const totalMod = allCells.length % 7;
        if (totalMod !== 0) {
            const nextPad = 7 - totalMod;
            for (let dayNum = 1; dayNum <= nextPad; dayNum++) {
                const nextMonthDate = new Date(currentYear, currentMonth, dayNum);
                const y = nextMonthDate.getFullYear();
                const m = String(nextMonthDate.getMonth() + 1).padStart(2, '0');
                const d = String(dayNum).padStart(2, '0');
                const dateStr = `${y}-${m}-${d}`;
                const isWeekend = (nextMonthDate.getDay() === 0 || nextMonthDate.getDay() === 6);

                allCells.push({
                    dayNum: dayNum,
                    dateStr: dateStr,
                    isOtherMonth: true,
                    isWeekend: isWeekend,
                    data: presensiMap[dateStr] || null
                });
            }
        }

        // Build HTML for Grid
        allCells.forEach(cell => {
            const isSelected = (cell.dateStr === selectedDate);
            const isToday = (cell.dateStr === todayStr);

            let classes = ['day-cell'];
            if (cell.isOtherMonth) classes.push('other-month');
            if (cell.isWeekend) classes.push('weekend');
            if (isSelected) classes.push('selected');
            if (isToday) classes.push('is-today');

            // Determine bottom indicator
            let indicatorHtml = '';
            const rec = cell.data;

            if (rec) {
                if (rec.status === 'h') {
                    // Checkmark badge if at least one presensi recorded
                    if (rec.jam_in || rec.jam_out) {
                        indicatorHtml = `<span class="check-badge">✓</span>`;
                    }
                } else if (rec.status === 'i') {
                    indicatorHtml = `<span class="badge-subtle badge-izin">I</span>`;
                } else if (rec.status === 's') {
                    indicatorHtml = `<span class="badge-subtle badge-sakit">S</span>`;
                } else if (rec.status === 'c') {
                    indicatorHtml = `<span class="badge-subtle badge-cuti">C</span>`;
                } else if (rec.status === 'tl') {
                    indicatorHtml = `<span class="badge-subtle badge-tl">TL</span>`;
                } else if (rec.status === 'l') {
                    indicatorHtml = `<span class="badge-subtle badge-libur">L</span>`;
                }
            } else {
                // No presensi: if past day (before today) and weekday, show small dot
                if (cell.dateStr < todayStr && !cell.isWeekend && !cell.isOtherMonth) {
                    indicatorHtml = `<span class="dot-past"></span>`;
                }
            }

            const cellEl = document.createElement('div');
            cellEl.className = classes.join(' ');
            cellEl.setAttribute('data-date', cell.dateStr);
            cellEl.innerHTML = `
                <div class="day-circle">${cell.dayNum}</div>
                <div class="day-indicator-wrapper">${indicatorHtml}</div>
            `;

            cellEl.addEventListener('click', function() {
                selectDate(cell.dateStr);
            });

            grid.appendChild(cellEl);
        });

        // Render Cards for currently selected date
        renderDetailCards(selectedDate);
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;

        // If clicked date is in different month/year, switch to that month
        const parts = dateStr.split('-');
        const targetYear = parseInt(parts[0]);
        const targetMonth = parseInt(parts[1]);

        if (targetYear !== currentYear || targetMonth !== currentMonth) {
            currentYear = targetYear;
            currentMonth = targetMonth;
            fetchMonthData(currentMonth, currentYear, function() {
                renderCalendar();
            });
            return;
        }

        // Update active class on grid
        document.querySelectorAll('.day-cell').forEach(el => {
            if (el.getAttribute('data-date') === dateStr) {
                el.classList.add('selected');
            } else {
                el.classList.remove('selected');
            }
        });

        renderDetailCards(dateStr);
    }

    function renderDetailCards(dateStr) {
        const container = document.getElementById('attendanceDetailSection');
        const formattedDate = formatIndoFullDate(dateStr);
        const rec = presensiMap[dateStr] || null;

        const parts = dateStr.split('-');
        const dateObj = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        const isWeekend = (dateObj.getDay() === 0 || dateObj.getDay() === 6);
        const isFuture = (dateStr > todayStr);

        let html = '';

        if (rec && rec.status === 'h') {
            // PRESENSI MASUK CARD
            const hasIn = Boolean(rec.jam_in);
            const fotoIn = rec.foto_in_url;
            const jamIn = hasIn ? rec.jam_in : 'Belum Absen';
            const windowInStart = rec.awal_jam_masuk || (defaultSchedule && defaultSchedule.awal_jam_masuk) || '07:15:00';
            const windowInEnd = rec.akhir_jam_masuk || (rec.jam_masuk || (defaultSchedule && defaultSchedule.jam_masuk) || '08:00:00');
            const isLate = Boolean(hasIn && rec.jam_masuk && (rec.jam_in > rec.jam_masuk));
            const isIzinTerlambat = (rec.status_in === 'izin_terlambat');

            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Presensi Masuk</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill ${isIzinTerlambat ? 'pill-blue' : (hasIn ? (isLate ? 'pill-warning' : 'pill-hadir') : 'pill-warning')}">
                                ${isIzinTerlambat ? 'Izin Terlambat' : (hasIn ? (isLate ? 'Terlambat' : 'Hadir') : 'Terlambat')}
                            </span>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        ${(hasIn && fotoIn) 
                            ? `<img src="${fotoIn}" class="detail-thumb" alt="Foto Masuk" onerror="this.src='/assets/img/sample/avatar/avatar1.jpg'">`
                            : `<div class="detail-thumb-placeholder"><ion-icon name="person-outline"></ion-icon></div>`
                        }
                        <div class="flex-grow-1">
                            <div class="time-display ${hasIn ? '' : 'text-muted'}" style="${hasIn ? '' : 'color: #94a3b8 !important; font-size: 20px;'}">${hasIn ? rec.jam_in : 'Tidak Absen Pagi'}</div>
                            <div class="time-subtitle">Masuk : ${windowInStart} - ${windowInEnd}</div>
                            ${isIzinTerlambat
                                ? `<span class="text-info status-badge-inline" style="color:#0284c7 !important;">ℹ️ Disetujui Izin Terlambat</span>`
                                : (hasIn 
                                    ? (isLate 
                                        ? `<span class="text-danger status-badge-inline">⚠️ Terlambat</span>` 
                                        : `<span class="text-success status-badge-inline">✓ Tepat Waktu</span>`)
                                    : `<span class="text-danger status-badge-inline">⚠️ Terlambat (Tidak Absen Pagi)</span>`)
                            }
                        </div>
                    </div>
                </div>
            `;

            // PRESENSI PULANG CARD
            const hasOut = Boolean(rec.jam_out);
            const fotoOut = rec.foto_out_url;
            const jamOut = hasOut ? rec.jam_out : 'Belum Absen';
            const windowOutStart = rec.jam_pulang || (defaultSchedule && defaultSchedule.jam_pulang) || '16:00:00';
            const windowOutEnd = rec.akhir_jam_pulang || (defaultSchedule && defaultSchedule.akhir_jam_pulang) || '23:59:00';
            const isEarly = Boolean(hasOut && (rec.status_out === 'pulang_duluan' || (rec.jam_pulang && (rec.jam_out < rec.jam_pulang))));
            const isIzinPulangCepat = (rec.status_out === 'izin_pulang_cepat');

            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Presensi Pulang</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill ${isIzinPulangCepat ? 'pill-blue' : (hasOut ? 'pill-hadir' : 'pill-secondary')}">
                                ${isIzinPulangCepat ? 'Izin Pulang Cepat' : (hasOut ? 'Hadir' : 'Belum Absen')}
                            </span>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        ${(hasOut && fotoOut) 
                            ? `<img src="${fotoOut}" class="detail-thumb" alt="Foto Pulang" onerror="this.src='/assets/img/sample/avatar/avatar1.jpg'">`
                            : `<div class="detail-thumb-placeholder"><ion-icon name="time-outline"></ion-icon></div>`
                        }
                        <div class="flex-grow-1">
                            <div class="time-display ${hasOut ? '' : 'text-muted'}" style="${hasOut ? '' : 'color: #94a3b8 !important; font-size: 22px;'}">${jamOut}</div>
                            <div class="time-subtitle">Pulang : ${windowOutStart} - ${windowOutEnd}</div>
                            ${isIzinPulangCepat
                                ? `<span class="text-primary status-badge-inline" style="color:#2563eb !important;">ℹ️ Disetujui Izin Pulang Cepat</span>`
                                : (hasOut
                                    ? (isEarly
                                        ? `<span class="text-warning status-badge-inline">⚠️ Pulang Lebih Awal</span>`
                                        : `<span class="text-success status-badge-inline">✓ Sudah Absen Pulang</span>`)
                                    : `<span class="text-muted status-badge-inline">Belum melakukan absen pulang</span>`)
                            }
                        </div>
                    </div>
                </div>
            `;
        } else if (rec && rec.status === 'i') {
            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Pengajuan Izin</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill pill-warning">Izin</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 text-muted font-weight-bold">Keterangan:</p>
                        <p class="mb-0 text-dark">${rec.keterangan || '-'}</p>
                    </div>
                </div>
            `;
        } else if (rec && rec.status === 's') {
            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Surat Izin Sakit</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill pill-blue">Sakit</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 text-muted font-weight-bold">Keterangan:</p>
                        <p class="mb-2 text-dark">${rec.keterangan || '-'}</p>
                        ${rec.doc_sid 
                            ? `<a href="/storage/uploads/sid/${rec.doc_sid}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                <ion-icon name="document-attach-outline"></ion-icon> Lihat Surat Dokter (SID)
                               </a>` 
                            : ''
                        }
                    </div>
                </div>
            `;
        } else if (rec && rec.status === 'c') {
            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Cuti: ${rec.nama_cuti || ''}</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill" style="background:#8b5cf6;">Cuti</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 text-muted font-weight-bold">Keterangan:</p>
                        <p class="mb-0 text-dark">${rec.keterangan || '-'}</p>
                    </div>
                </div>
            `;
        } else if (rec && rec.status === 'tl') {
            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Tugas Luar</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill" style="background:#059669;">Tugas Luar</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1 text-muted font-weight-bold">Keterangan Tugas:</p>
                        <p class="mb-0 text-dark">${rec.keterangan || '-'}</p>
                    </div>
                </div>
            `;
        } else if (rec && rec.status === 'l') {
            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Libur Nasional / Bersama</h3>
                            <div class="detail-card-date">${formattedDate}</div>
                        </div>
                        <div>
                            <span class="status-pill pill-success">Libur</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-0 font-weight-bold text-dark">${rec.keterangan || 'Hari Libur Nasional'}</p>
                    </div>
                </div>
            `;
        } else if (dateStr === todayStr) {
            // Hari kerja hari ini yang sedang berlangsung namun pegawai belum presensi sama sekali
            const windowInStart = (defaultSchedule && defaultSchedule.awal_jam_masuk) || '07:15:00';
            const windowInEnd = (defaultSchedule && (defaultSchedule.akhir_jam_masuk || defaultSchedule.jam_masuk)) || '08:00:00';
            const windowOutStart = (defaultSchedule && defaultSchedule.jam_pulang) || '16:00:00';
            const windowOutEnd = (defaultSchedule && defaultSchedule.akhir_jam_pulang) || '23:59:00';

            html += `
                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Presensi Masuk</h3>
                            <div class="detail-card-date">${formattedDate} (Hari Ini)</div>
                        </div>
                        <div>
                            <span class="status-pill pill-secondary">Belum Absen</span>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        <div class="detail-thumb-placeholder"><ion-icon name="person-outline"></ion-icon></div>
                        <div class="flex-grow-1">
                            <div class="time-display text-muted" style="color: #94a3b8 !important; font-size: 22px;">Belum Absen</div>
                            <div class="time-subtitle">Masuk : ${windowInStart} - ${windowInEnd}</div>
                            <span class="text-danger status-badge-inline">Belum melakukan absen masuk</span>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-card-header">
                        <div>
                            <h3 class="detail-card-title">Presensi Pulang</h3>
                            <div class="detail-card-date">${formattedDate} (Hari Ini)</div>
                        </div>
                        <div>
                            <span class="status-pill pill-secondary">Belum Absen</span>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        <div class="detail-thumb-placeholder"><ion-icon name="time-outline"></ion-icon></div>
                        <div class="flex-grow-1">
                            <div class="time-display text-muted" style="color: #94a3b8 !important; font-size: 22px;">Belum Absen</div>
                            <div class="time-subtitle">Pulang : ${windowOutStart} - ${windowOutEnd}</div>
                            <span class="text-muted status-badge-inline">Belum melakukan absen pulang</span>
                        </div>
                    </div>
                </div>
            `;
        } else if (isWeekend) {
            html += `
                <div class="empty-card">
                    <div class="empty-icon text-warning">
                        <ion-icon name="cafe-outline"></ion-icon>
                    </div>
                    <h4 class="empty-title">Libur Akhir Pekan (Sabtu / Minggu)</h4>
                    <div class="text-muted mb-2" style="font-size:13px;">${formattedDate}</div>
                    <p class="empty-desc">Hari ini merupakan hari libur akhir pekan, tidak ada jadwal presensi kerja.</p>
                </div>
            `;
        } else if (isFuture) {
            html += `
                <div class="empty-card">
                    <div class="empty-icon text-primary">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <h4 class="empty-title">Tanggal Belum Berjalan</h4>
                    <div class="text-muted mb-2" style="font-size:13px;">${formattedDate}</div>
                    <p class="empty-desc">Belum ada catatan presensi pada tanggal mendatang.</p>
                </div>
            `;
        } else {
            // Past working day without presensi
            html += `
                <div class="empty-card">
                    <div class="empty-icon text-danger">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                    </div>
                    <h4 class="empty-title">Tidak Ada Presensi</h4>
                    <div class="text-muted mb-2" style="font-size:13px;">${formattedDate}</div>
                    <p class="empty-desc text-danger">Tidak ditemukan rekaman presensi masuk atau pulang pada hari kerja ini.</p>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    function fetchMonthData(m, y, callback) {
        $.ajax({
            type: 'POST',
            url: '/gethistori',
            data: {
                _token: "{{ csrf_token() }}",
                bulan: m,
                tahun: y
            },
            dataType: 'json',
            success: function(res) {
                const list = (res && (res.histori || res.data)) ? (res.histori || res.data) : [];
                populatePresensiMap(list);
                if (res && res.default_jam_kerja) {
                    defaultSchedule = res.default_jam_kerja;
                }
                if (callback) callback();
            },
            error: function(err) {
                console.error('Error fetching month data', err);
                if (callback) callback();
            }
        });
    }

    $(document).ready(function() {
        // Initial render
        renderCalendar();

        // Previous Month Button
        $('#btnPrevMonth').click(function() {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            // Auto select 1st day of target month or keep date
            selectedDate = `${currentYear}-${String(currentMonth).padStart(2, '0')}-01`;
            fetchMonthData(currentMonth, currentYear, function() {
                renderCalendar();
            });
        });

        // Next Month Button
        $('#btnNextMonth').click(function() {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            selectedDate = `${currentYear}-${String(currentMonth).padStart(2, '0')}-01`;
            fetchMonthData(currentMonth, currentYear, function() {
                renderCalendar();
            });
        });
    });
</script>
@endpush
