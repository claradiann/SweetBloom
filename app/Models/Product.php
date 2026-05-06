<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'emoji',
        'category', 'badge', 'rating', 'is_available',
    ];
}