<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = "penerimaan";
    protected $primaryKey = "kode_penerimaan";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_penerimaan',
        'nik',
        'tgl_penerimaan',
        'kode_cabang',
        'status',
        'status_approved',
        'notice_keluar',
        'r2',
        'r4',
        'jumlah',
        'doc_sid',
    ];
}
