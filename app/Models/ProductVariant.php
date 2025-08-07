<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\ObjectIdCast;

class ProductVariant extends BaseModel
{
    protected $table = 'uni_product_variant';
    
    protected $fillable = [
        'product_id',
        'sku',
        'stock',
        'attributes',
        'status'
    ];

    protected $casts = [
        'product_id' => ObjectIdCast::class,
        'stock' => 'integer',
        'attributes' => 'array',
        'status' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 