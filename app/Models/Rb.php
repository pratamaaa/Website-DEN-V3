<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rb extends Model
{
    protected $table = 'rb';

    protected $primaryKey = 'id_rb';

    protected $fillable = [
        'id_rbkategori',
        'judul',
        'judul_en',
        'deskripsi',
        'deskripsi_en',
        'gambar_sampul',
        'berkas',
        'tanggal_publikasi',
    ];

    public function kategori()
    {
        return $this->belongsTo(Rbkategori::class, 'id_rbkategori', 'id_rbkategori');
    }
}
