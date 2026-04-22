<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infografis extends Model
{
    protected $table = 'infografis';

    protected $primaryKey = 'id_infografis';

    protected $fillable = [
        'judul_infografis',
        'gambar_sampul',
        'berkas_sumber',
        'berkas',
        'is_active',
        'tanggal_publikasi',
    ];
}
