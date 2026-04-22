<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerResponden extends Model
{
    protected $table = 'kuesioner_responden';
    protected $guarded = ['kuesioner_responden_id'];
    protected $primaryKey = 'kuesioner_responden_id';
    public $incrementing = true;
    public $timestamps = false;
   
    protected $fillable = [
        'kuesioner_responden_uuid',
        'kuesioner_layanan_uuid',
        'kuesioner_responden_nama',
        'kuesioner_responden_email',
        'kuesioner_responden_telp',
        'kuesioner_responden_instansi_asal',
        'kuesioner_responden_pemangku_kepentingan',
        'kuesioner_responden_kementerian_lembaga',
        'kuesioner_responden_instansi_asal_uuid',       // <--- Baru
        'kuesioner_responden_pemangku_kepentingan_uuid', // <--- Baru
        'kuesioner_responden_kementerian_lembaga_uuid',  // <--- Baru
        'kuesioner_responden_saran',
        'kuesioner_responden_created_by',
        'kuesioner_responden_created_date',
        'kuesioner_responden_update_by',
        'kuesioner_responden_update_date',
        'kuesioner_responden_status',
    ];
}
