<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rbkategori extends Model
{
    protected $table = 'rb_kategori';

    protected $primaryKey = 'id_rbkategori';

    protected $fillable = [
        'nama_rbkategori',
        'nama_rbkategori_en',
        'slug',
        'urutan',
    ];
}
