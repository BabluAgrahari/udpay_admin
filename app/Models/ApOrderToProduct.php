<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Product;
use App\Models\ProductVariant;

class ApOrderToProduct extends BaseModel  
{
  
    protected $table='ap_order_to_products';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

} 