<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\ObjectIdCast;

class Category extends BaseModel
{

    protected $fillable = [
        'name',
        'description',
        'icon',
        'short',
        'status',
        'parent_id',
        'user_id',
        'labels',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'user_id'
    ];

    protected $casts = [
        'parent_id' => ObjectIdCast::class,
        'user_id' => ObjectIdCast::class,
        'labels' => 'array',
        'status' => 'integer',
        'meta_title' => 'string',
        'meta_keyword' => 'string',
        'meta_description' => 'string'
    ];

    // Relationship with parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relationship with child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 