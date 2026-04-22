<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugasfungsi extends Model
{
    protected $table = 'tugasfungsi';

    protected $primaryKey = 'id_tf';

    protected $fillable = ['konten', 'konten_en'];
}
