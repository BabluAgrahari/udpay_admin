<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\UserAddress;

class DealOrder extends BaseModel
{
   protected $table = 'deals_order';

   public function products()
   {
      return $this->hasMany(DealOrderToProduct::class, 'order_id', 'id');
   }

   public function address()
   {
      return $this->hasOne(UserAddress::class, 'id', 'address_id');
   }

} 