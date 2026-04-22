<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerJawabanResponden extends Model
{
    protected $table = 'kuesioner_jawaban_responden';
    protected $guarded = ['kuesioner_jawaban_responden_id'];
    protected $primaryKey = 'kuesioner_jawaban_responden_id';
    public $incrementing = true; 
    public $timestamps = false;
}

