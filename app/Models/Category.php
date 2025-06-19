<?php

namespace App\Models;

use App\Models\BaseModel;

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
        'labels'
    ];

    protected $casts = [
        'labels' => 'array',
        'status' => 'integer'
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