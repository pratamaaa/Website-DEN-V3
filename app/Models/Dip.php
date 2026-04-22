<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dip extends Model
{
    protected $table = 'daftar_informasi_publik';

    protected $primaryKey = 'id_dip';

    protected $fillable = [
        'ringkasan_isi',
        'pic_satker',
        'penanggungjawab',
        'waktu_tempat',
        'bentuk_informasi',
        'retensi_arsip',
    ];
}
