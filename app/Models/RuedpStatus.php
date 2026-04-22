<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuedpStatus extends Model
{
    protected $table = 'ruedp_status';

    protected $primaryKey = 'id_ruedp_status';

    protected $fillable = ['nama_status', 'warna', 'urutan'];

    public function provinsi()
    {
        return $this->hasMany(RuedpProvinsi::class, 'id_ruedp_status', 'id_ruedp_status');
    }
}
