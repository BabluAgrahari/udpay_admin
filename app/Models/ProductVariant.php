<?php

namespace App\Models;

use App\Models\BaseModel;


class ProductVariant extends BaseModel
{
    protected $table = 'uni_product_variant';
    
    protected $fillable = [
        'product_id',
        'sku',
        'stock',
        'variant_name',
        'price',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 