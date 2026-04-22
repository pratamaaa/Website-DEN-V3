<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerJawaban extends Model
{
    protected $table = 'kuesioner_jawaban';
    protected $guarded = ['kuesioner_jawaban_id'];
    protected $primaryKey = 'kuesioner_jawaban_id';
    public $incrementing = true; 
    public $timestamps = false;
}

