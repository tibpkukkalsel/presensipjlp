<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfigurasiLaporan extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_laporan';

    protected $fillable = [
        'pejabat1_jabatan',
        'pejabat1_nama',
        'pejabat1_nip',
        'pejabat2_jabatan',
        'pejabat2_nama',
        'pejabat2_nip',
        'kota_cetak',
    ];
}
