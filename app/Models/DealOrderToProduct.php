<?php

namespace App\Models;

use App\Models\BaseModel;

class DealOrderToProduct extends BaseModel
{
   protected $table = 'deals_order_to_products';

   public function product()
   {
      return $this->hasOne(Product::class, 'id', 'product_id');
   }

   public function productVariant()
   {
      return $this->hasOne(ProductVariant::class, 'id', 'product_variant_id');
   }
} 