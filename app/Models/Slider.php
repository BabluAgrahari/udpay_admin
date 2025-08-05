<?php

namespace App\Models;

use App\Models\BaseModel;

class Slider extends BaseModel
{
    protected $table = 'sliders';
    protected $fillable = [
        'title',
        'slider_image',
        'slider_type',
        'cat_id',
        'status',
        'url',
        'created_at',
        'updated_at',
    ];
} 