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
        'icon',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'user_id',
        'user_id'
    ];

    protected $casts = [
        'status' => 'integer',
        'meta_title' => 'string',
        'meta_description' => 'string',
        'user_id' => ObjectIdCast::class,
        'meta_keyword' => 'string'
    ];
} 