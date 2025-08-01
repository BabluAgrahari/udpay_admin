<?php

namespace App\Models;

use App\Models\BaseModel;

class Category extends BaseModel
{

    protected $table = 'uni_category';

    protected $fillable = [
        'parent_id',
        'name',
        'img',
        'pro_section', //primary, deals
        'status',
        'meta_title',
        'meta_keyword',
        'meta_description'
    ];
    // Relationship with parent category
    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
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