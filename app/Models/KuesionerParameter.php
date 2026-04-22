<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuesionerParameter extends Model
{
    protected $table = 'kuesioner_parameter';
    protected $guarded = ['kuesioner_parameter_id'];
    protected $primaryKey = 'kuesioner_parameter_id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'kuesioner_parameter_id',
        'kuesioner_parameter_uuid',
        'kuesioner_parameter_code',
        'kuesioner_parameter_nama',
        'kuesioner_parameter_created_by',
        'kuesioner_parameter_created_date',
        'kuesioner_parameter_update_by',
        'kuesioner_parameter_update_date',
        'kuesioner_parameter_status',
        'kuesioner_parameter_log_uuid',
        'kuesioner_parameter_log_id',
    ];
}
