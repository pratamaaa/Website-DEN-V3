<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerLayanan extends Model
{
    protected $table = 'kuesioner_layanan';
    protected $guarded = ['kuesioner_layanan_id'];
    protected $primaryKey = 'kuesioner_layanan_id';
    public $incrementing = true; 
    public $timestamps = false;
}
