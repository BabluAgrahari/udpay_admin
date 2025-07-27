<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\ObjectIdCast;
use App\Models\Order;

class Stock extends BaseModel
{
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'stock',
        'type',
        'remarks',
        'user_id',
        'unit_id',
        'order_id',
        'closing_stock'
    ];

    protected $casts = [
        'product_id' => ObjectIdCast::class,
        'product_variant_id' => ObjectIdCast::class,
        'stock' => 'float',
        'type' => 'string',
        'remarks' => 'string',
        'user_id' => ObjectIdCast::class,
        'unit_id' => ObjectIdCast::class,
        'order_id' => ObjectIdCast::class,
        'closing_stock' => 'float'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
} 