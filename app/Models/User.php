<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role',
        'is_confirmed', 'confirm_token', 'confirm_expires',
        'reset_token', 'reset_expires',
        'login_attempts', 'locked_until',
    ];

    protected $hidden = ['password', 'confirm_token', 'reset_token'];

    protected $casts = [
        'is_confirmed'   => 'boolean',
        'confirm_expires'=> 'datetime',
        'reset_expires'  => 'datetime',
        'locked_until'   => 'datetime',
    ];
}