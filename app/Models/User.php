<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens; // ← INI YANG KURANG!

    protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'role',
];

    protected $hidden = ['password', 'confirm_token', 'reset_token'];

    protected $casts = [
        'is_confirmed'   => 'boolean',
        'confirm_expires'=> 'datetime',
        'reset_expires'  => 'datetime',
        'locked_until'   => 'datetime',
    ];
}