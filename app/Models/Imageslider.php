<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imageslider extends Model
{
    protected $table = 'image_slider';

    protected $primaryKey = 'id_image_slider';

    protected $fillable = [
        'judul_slider',
        'alamat_url',
        'gambar',
        'is_active',
        'tanggal_publikasi',
    ];
}
