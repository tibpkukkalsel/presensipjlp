<form action="/panel-adm14/storekoreksipresensi" method="POST" id="formKoreksipresensi">
    @csrf
    <input type="hidden" name="nik" value="{{ $karyawan->nik }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    <div class="card card-sm mb-3 bg-light">
        <div class="card-body py-2 px-3">
            <div class="row">
                <div class="col-4 text-muted small">NIK / ID</div>
                <div class="col-8 fw-bold small">: {{ $karyawan->nik }}</div>
            </div>
            <div class="row">
                <div class="col-4 text-muted small">Nama Pegawai</div>
                <div class="col-8 fw-bold small">: {{ $karyawan->nama_lengkap }}</div>
            </div>
            <div class="row">
                <div class="col-4 text-muted small">Tanggal Presensi</div>
                <div class="col-8 fw-bold small">: {{ date('d-m-Y', strtotime($tanggal)) }}</div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label class="form-label fw-bold">Status Kehadiran <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select">
                <option value="">Pilih Status Kehadiran</option>
                <option value="h" {{ ($presensi != null && $presensi->status === 'h') ? 'selected' : '' }}>Hadir</option>
                <option value="i" {{ ($presensi != null && $presensi->status === 'i') ? 'selected' : '' }}>Izin</option>
                <option value="s" {{ ($presensi != null && $presensi->status === 's') ? 'selected' : '' }}>Sakit</option>
                <option value="c" {{ ($presensi != null && $presensi->status === 'c') ? 'selected' : '' }}>Cuti</option>
                <option value="a" {{ ($presensi != null && $presensi->status === 'a') ? 'selected' : '' }}>Alfa</option>
            </select>
        </div>
    </div>
    <div class="row mb-3" id="frm_jam_in">
        <div class="col-12">
            <label class="form-label">Jam Masuk (Format: HH:mm:ss)</label>
            <div class="input-icon">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="20"
                        height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                <input type="text" id="jam_in" value="{{ $presensi != null ? $presensi->jam_in : '' }}"
                    class="form-control" placeholder="Contoh: 07:30:00" name="jam_in">
            </div>
        </div>
    </div>
    <div class="row mb-3" id="frm_jam_out">
        <div class="col-12">
            <label class="form-label">Jam Pulang (Format: HH:mm:ss)</label>
            <div class="input-icon">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock" width="20"
                        height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 7v5l3 3" />
                    </svg>
                </span>
                <input type="text" id="jam_out" value="{{ $presensi != null ? $presensi->jam_out : '' }}"
                    class="form-control" placeholder="Contoh: 16:00:00" name="jam_out">
            </div>
        </div>
    </div>
    <div class="row mb-3" id="frm_kode_jam_kerja">
        <div class="col-12">
            <label class="form-label">Jadwal Jam Kerja</label>
            <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select">
                <option value="">Pilih Jam Kerja</option>
                @foreach ($jamkerja as $d)
                    <option value="{{ $d->kode_jam_kerja }}"
                        {{ ($presensi != null && $presensi->kode_jam_kerja === $d->kode_jam_kerja) ? 'selected' : '' }}>
                        {{ $d->nama_jam_kerja }} ({{ substr($d->jam_masuk, 0, 5) }} - {{ substr($d->jam_pulang, 0, 5) }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"/><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M14 4l0 4l-6 0l0 -4"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        function loadkoreksi() {
            var status = $("#status").val();
            if (status !== "h") {
                $("#frm_jam_in").hide();
                $("#frm_jam_out").hide();
                $("#frm_kode_jam_kerja").hide();
            } else {
                $("#frm_jam_in").show();
                $("#frm_jam_out").show();
                $("#frm_kode_jam_kerja").show();
            }
        }

        loadkoreksi();

        $("#status").change(function(e) {
            loadkoreksi();
        });

        $("#formKoreksipresensi").submit(function() {
            var status = $("#status").val();
            var kode_jam_kerja = $("#kode_jam_kerja").val();

            if (status === "") {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Status kehadiran harus dipilih!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#status").focus();
                });
                return false;
            } else if (status === "h" && kode_jam_kerja === "") {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Jadwal jam kerja harus dipilih untuk status Hadir!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $("#kode_jam_kerja").focus();
                });
                return false;
            }
            return true;
        });
    });
</script>
