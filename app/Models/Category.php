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

    protected $casts = [
        'parent_id' => 'integer',
        'status' => 'boolean',//0 = inactive, 1 = active
        'pro_section' => 'string', // primary, deals
        'img' => 'string', // image path
        'meta_title' => 'string',
        'meta_keyword' => 'string',
        'meta_description' => 'string'
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

    // Accessor for cat_role based on parent_id
    public function getCatRoleAttribute()
    {
        return $this->parent_id == 0 ? 'super' : 'sub';
    }

    // Accessor for slug based on name
    public function getSlugAttribute()
    {
        return \Str::slug($this->name);
    }
}