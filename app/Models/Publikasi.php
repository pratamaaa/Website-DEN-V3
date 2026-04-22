<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    protected $table = 'publikasi';

    protected $primaryKey = 'id_publikasi';

    public $timestamps = false;

    protected $guarded = [];
}
