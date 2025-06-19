<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductVariant extends BaseModel
{
    protected $fillable = [
        'product_id',
        'sku',
        'stock',
        'attributes',
        'status'
    ];

    protected $casts = [
        'stock' => 'integer',
        'attributes' => 'array',
        'status' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 