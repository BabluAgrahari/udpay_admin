<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\OrderToProduct;

class Order extends BaseModel
{
  
    protected $table='deals_order';

    public function orderToProducts()
    {
        return $this->hasMany(OrderToProduct::class, 'order_id', 'id');
    }

} 