<?php

namespace App\Models;

use App\Models\BaseModel;

class Wishlist extends BaseModel
{
    protected $table = 'uni_wishlist';


    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}