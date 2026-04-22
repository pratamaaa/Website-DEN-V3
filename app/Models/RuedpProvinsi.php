<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuedpProvinsi extends Model
{
    protected $table = 'ruedp_provinsi';

    protected $primaryKey = 'id_ruedp_provinsi';

    protected $fillable = [
        'id_ruedp_status',
        'nama_provinsi',
        'kode_provinsi',
        'nomor_perda',
        'tanggal_update',
        'keterangan',
    ];

    public function status()
    {
        return $this->belongsTo(RuedpStatus::class, 'id_ruedp_status', 'id_ruedp_status');
    }
}
