<?php

namespace App\Models;

use App\Models\BaseModel;

class Slider extends BaseModel
{
    protected $fillable = [
        'title',
        'image',
        'type',
        'status',
        'url',
    ];
} 