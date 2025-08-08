<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductReview extends BaseModel
{
    protected $table = 'users_review';


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','uid');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id','product_id');
    }
}