<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends BaseModel
{
    protected $table = 'uni_brand';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'icon',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];
} 