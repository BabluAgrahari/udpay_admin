<?php

namespace App\Models;

use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $table = 'uni_products';

    
    protected $fillable = [
        'is_combo',
        'combo_id',
        'product_name',
        'slug_url',
        'product_category_id',
        'product_image',
        'brand_id',
        'product_price',
        'product_sale_price',
        'mrp',
        'product_stock',
        'unit_id',
        'product_description',
        'product_short_description',
        'product_meta_title',
        'product_meta_keywords',
        'meta_description',
        'product_min_qty',
        'igst',
        'is_featured',
        'on_slider',
        'on_banner',
        'up',
        'sv',
        'offer',
        'offer_date',
        'hsn_code',
        'sku_code',
        'created_by',
        'updated_by',
        'status',
        'mart_status',
        'pro_type',
        'pro_section'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function ingredients()
    {
        return $this->hasOne(ProductDetail::class, 'product_id');
    }

    public function reels()
    {
        return $this->hasMany(ProductReel::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }


}
