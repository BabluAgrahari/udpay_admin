<?php

namespace App\Models;

use App\Casts\ObjectIdCast;
use App\Models\BaseModel;
use App\Observers\Timestamp;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\ObjectId;

class Wishlist extends BaseModel
{
    protected $table = 'uni_wishlist';


    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

}