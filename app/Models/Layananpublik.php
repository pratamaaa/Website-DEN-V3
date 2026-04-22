<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layananpublik extends Model
{
    protected $table = 'layanan_publik';

    protected $primaryKey = 'id_layananpublik';

    protected $fillable = [
        'nama_layananpublik',
        'nama_layananpublik_en',
        'deskripsi',
        'deskripsi_en',
        'icon',
        'alamat_url',
        'urutan',
    ];
}
