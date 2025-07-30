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
        'varient_name',
        'price',
        'status'
    ];

    protected $casts = [
        'product_id' => ObjectIdCast::class,
        'stock' => 'integer',
        'price' => 'decimal:2',
        'status' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 