<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galerivideo extends Model
{
    protected $table = 'galerivideo';

    protected $primaryKey = 'id_galerivideo';

    protected $fillable = [
        'judul',
        'deskripsi',
        'youtube_id',
        'tanggal_publikasi',
        'file',
    ];
}
