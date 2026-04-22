<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerTemplateJawabanGroup extends Model
{
    protected $table = 'kuesioner_template_jawaban_group';
    protected $guarded = ['kuesioner_template_jawaban_group_id'];
    protected $primaryKey = 'kuesioner_template_jawaban_group_id';
    public $incrementing = true; 
    public $timestamps = false;
}
