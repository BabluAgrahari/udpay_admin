<?php

namespace App\Models;

use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'product_name',
        'slug_url',
        'sku',
        'hsn_code',
        'mrp',
        'sale_price',
        'cgst',
        'up',
        'sv',
        'offer',
        'offer_date',
        'mart_status',
        'product_type',
        'product_section',
        'short_description',
        'description',
        'images',
        'thumbnail',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'status',
        'is_combo',
        'product_ids',
        'is_variant'
    ];

    protected $casts = [
        'mrp' => 'float',
        'sale_price' => 'float',
        'cgst' => 'float',
        'up' => 'float',
        'sv' => 'float',
        'offer' => 'boolean',
        'offer_date' => 'datetime',
        'mart_status' => 'boolean',
        'images' => 'array',
        'status' => 'boolean',
        'is_combo' => 'boolean',
        'product_ids' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', '_id');
    }
} 