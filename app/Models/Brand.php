<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends BaseModel
{
    protected $fillable = [
        'name',
        'slug_url',
        'description',
        'status',
        'icon'
    ];

    protected $casts = [
        'status' => 'integer'
    ];
} 