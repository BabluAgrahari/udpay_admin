<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\ApOrderToProduct;

class ApOrder extends BaseModel
{
  
    protected $table='ap_repurchase_order';

    public function orderToProducts()
    {
        return $this->hasMany(ApOrderToProduct::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }

    public function shipping_address()
    {
        return $this->hasOne(UserAddress::class, 'id', 'address_id');
    }

} 