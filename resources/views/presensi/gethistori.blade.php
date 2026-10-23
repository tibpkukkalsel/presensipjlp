<style>
    .historicontent {
        display: flex;
        margin-top: 10px;
    }

    .datapresensi {
        margin-left: 10px;
    }

    .card {
        border: 1px solid #2A8BF2;
    }
</style>

@if ($histori->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@endif
@foreach ($histori as $d)
    @if ($d->status == 'h')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="finger-print-outline" style="font-size: 48px;" class="text-success"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">{{ $d->nama_jam_kerja }}</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>
                            {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span class="text-danger">Belum Scan</span>' !!}
                        </span>
                        <span>
                            {!! $d->jam_out != null
                                ? '-' . date('H:i', strtotime($d->jam_out))
                                : '<span class="text-danger">- Belum Scan</span>' !!}
                        </span>
                        <br>
                        @php
                            $has_jam_in = !empty($d->jam_in);
                            $jam_masuk_jadwal = !empty($d->jam_masuk) ? date('H:i', strtotime($d->jam_masuk)) : '08:00';
                            $jam_in_formatted = $has_jam_in ? date('H:i', strtotime($d->jam_in)) : null;

                            $jadwal_jam_masuk = $d->tgl_presensi . ' ' . $jam_masuk_jadwal;
                            $jam_presensi = $d->tgl_presensi . ' ' . ($jam_in_formatted ?? '00:00');
                        @endphp
                        @if (!$has_jam_in)
                            <span class="danger text-danger font-weight-bold" style="color:#ef4444;">Terlambat (Tidak Absen Pagi)</span>
                        @elseif ($jam_in_formatted > $jam_masuk_jadwal)
                            @php
                                $jmlterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
                                $jmlterlambatdesimal = hitungjamterlambatdesimal($jadwal_jam_masuk, $jam_presensi);
                            @endphp
                            <span class="danger">Terlambat {{ $jmlterlambat }} ({{ $jmlterlambatdesimal }} Jam)</span>
                        @else
                            <span style="color:green">Tepat Waktu</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'tl')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="briefcase-outline" style="font-size: 48px;" class="text-info"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">TUGAS LUAR {{ !empty($d->kode_izin) ? '- ' . $d->kode_izin : '' }}</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>
                            {{ $d->keterangan }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'l')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="calendar-outline" style="font-size: 48px;" class="text-danger"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">LIBUR NASIONAL</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span class="text-danger font-weight-bold">
                            {{ !empty($d->keterangan) ? $d->keterangan : 'Libur Nasional / Bersama' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'i')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="document-outline" style="font-size: 48px;" class="text-warning"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">IZIN - {{ $d->kode_izin }}</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>
                            {{ $d->keterangan }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 's')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="medkit-outline" style="font-size: 48px;" class="text-primary"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">SAKIT - {{ $d->kode_izin }}</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span>
                            {{ $d->keterangan }}
                        </span>
                        <br>
                        @if (!empty($d->doc_sid))
                            <span style="color: blue">
                                <ion-icon name="document-attach-outline"></ion-icon> SID
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @elseif($d->status == 'c')
        <div class="card mb-1">
            <div class="card-body">
                <div class="historicontent">
                    <div class="iconpresensi">
                        <ion-icon name="document-outline" style="font-size: 48px;" class="text-info"></ion-icon>
                    </div>
                    <div class="datapresensi">
                        <h3 style="line-height: 3px">CUTI - {{ $d->kode_izin }}</h3>
                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</h4>
                        <span class="text-info">
                            {{ $d->nama_cuti }}
                        </span>
                        <br>
                        <span>
                            {{ $d->keterangan }}
                        </span>

                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
