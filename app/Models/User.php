<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'id_user_level',
        'nip',
        'kode_org',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'mfa_enabled'       => 'boolean',
    ];

    // Role helpers
    public function userLevel()
{
    return $this->belongsTo(\App\Models\UsersLevel::class, 'id_user_level');
}

public function getLevelAttribute()
{
    return $this->id_user_level;
}
}