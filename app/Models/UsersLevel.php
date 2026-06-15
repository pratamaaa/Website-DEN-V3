<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLevel extends Model
{
    protected $table = 'users_level';

    protected $primaryKey = 'id_user_level';

    public $timestamps = false;

    protected $fillable = ['level', 'keterangan'];
}
