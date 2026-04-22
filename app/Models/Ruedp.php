<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruedp extends Model
{
    protected $table = 'ruedp';

    protected $primaryKey = 'id_ruedp';

    protected $fillable = [
        'status_penyusunan',
        'jumlah_provinsi',
        'daftar_provinsi',
        'pertanggal',
        'urutan',
    ];
}
