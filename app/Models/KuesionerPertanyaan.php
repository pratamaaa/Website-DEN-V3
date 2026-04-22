<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerPertanyaan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kuesioner_pertanyaan';

    // Primary Key (ID auto-increment)
    protected $primaryKey = 'kuesioner_pertanyaan_id';
    public $incrementing = true;

    // Non-timestamps karena Anda menggunakan kolom custom created_date/update_date
    public $timestamps = false;

    // Kolom yang boleh diisi (kecuali PK)
    // Berdasarkan daftar kolom Anda, kuesioner_pertanyaan_id harus di-guarded.
    // Jika Anda ingin mengizinkan Mass Assignment, gunakan $fillable, atau $guarded.
    protected $guarded = ['kuesioner_pertanyaan_id']; 
    
    // Jika Anda ingin menggunakan Mass Assignment, Anda bisa mendefinisikan kolom yang diizinkan:
    /*
    protected $fillable = [
        'kuesioner_pertanyaan_uuid',
        'kuesioner_pertanyaan_parent_uuid',
        // ... (semua kolom yang boleh diisi)
    ];
    */

    // --- Relasi ---

    /**
     * Relasi ke Opsi Jawaban (KuesionerJawaban).
     * Dihubungkan menggunakan kolom UUID Pertanyaan.
     */
    public function children()
    {
        return $this->hasMany(
            KuesionerPertanyaan::class,
            'kuesioner_pertanyaan_parent_uuid',
            'kuesioner_pertanyaan_uuid'
        )
            ->where('kuesioner_pertanyaan_status', 1)
            ->whereNull('kuesioner_pertanyaan_log_uuid')
            ->with(['children', 'jawaban']);
    }
    // public function children()
    // {
    //     return $this->hasMany(
    //         KuesionerPertanyaan::class,
    //         'kuesioner_pertanyaan_parent_uuid',
    //         'kuesioner_pertanyaan_uuid'
    //     )
    //         ->whereNull('kuesioner_pertanyaan_parent_uuid')
    //         ->where('kuesioner_pertanyaan_status', 1)
    //         ->with('children'); // recursive
    // }

    public function jawaban()
    {
        return $this->hasMany(
            KuesionerJawaban::class,
            'kuesioner_jawaban_kuesioner_pertanyaan_uuid',
            'kuesioner_pertanyaan_uuid'
        )
            ->where('kuesioner_jawaban_status', 1)
            ->whereNull('kuesioner_jawaban_log_uuid')
            ->orderBy('kuesioner_jawaban_code', 'asc');
    }

    /**
     * Relasi ke Header/Parent (dirinya sendiri).
     */
    public function parent()
    {
        return $this->belongsTo(
            KuesionerPertanyaan::class,
            'kuesioner_pertanyaan_parent_uuid',
            'kuesioner_pertanyaan_uuid'
        );
    }
}
