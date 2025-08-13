<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class Wishlist extends BaseModel
{
    protected $table = 'uni_wishlist';


    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}