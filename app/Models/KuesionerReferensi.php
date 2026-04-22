<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerReferensi extends Model
{
    use HasFactory;

    protected $table = 'kuesioner_referensi';
    protected $primaryKey = 'referensi_id';
    
    // Konfigurasi timestamp custom sesuai struktur tabel Anda
    const CREATED_AT = 'referensi_created_date';
    const UPDATED_AT = 'referensi_update_date';

    protected $fillable = [
        'referensi_uuid',
        'referensi_kategori',
        'referensi_nama',
        'referensi_urutan',
        'referensi_created_by',
        'referensi_update_by',
        'referensi_status',
        'referensi_log_uuid',
        'referensi_log_id'
    ];
}