<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $primaryKey = 'id_berita';

    public $timestamps = false;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($model) {
            audit_log('Tambah berita: '.$model->judul, 'Berita');
        });

        static::updated(function ($model) {
            audit_log('Update berita: '.$model->judul, 'Berita');
        });

        static::deleted(function ($model) {
            audit_log('Hapus berita: '.$model->judul, 'Berita');
        });
    }
}
