<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\ObjectIdCast;

class Product extends BaseModel
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'unit_id',
        'product_name',
        'slug_url',
        'sku',
        'hsn_code',
        'mrp',
        'sale_price',
        'gst',
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
        'is_variant',
        'bonus_point'
    ];

    protected $casts = [
        'product_name' => 'string',
        'slug_url' => 'string',
        'sku' => 'string',
        'hsn_code' => 'string',
        'unit_id' => ObjectIdCast::class,
        'brand_id' => ObjectIdCast::class,
        'category_id' => ObjectIdCast::class,
        'short_description' => 'string',
        'description' => 'string',
        'images' => 'array',
        'thumbnail' => 'string',
        'meta_title' => 'string',
        'meta_keyword' => 'string',
        'meta_description' => 'string',
        'mrp' => 'float',
        'sale_price' => 'float',
        'gst' => 'float',
        'up' => 'float',
        'sv' => 'float',
        'offer' => 'integer',
        'offer_date' => 'datetime',
        'mart_status' => 'integer',
        'images' => 'array',
        'status' => 'integer',
        'is_combo' => 'integer',
        'product_ids' => 'array',
        'is_variant' => 'integer',
        'bonus_point' => 'float'
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
