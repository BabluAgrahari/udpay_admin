<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Cart extends BaseModel
{
    use HasFactory;
      protected $table = 'uni_cart';

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'cart_cookie_id',
        'cart_type',
        'variant_id',
        'attribute_id'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'product_id' => 'integer',//0 = inactive, 1 = active
        'quantity' => 'integer', // primary, deals
        'cart_cookie_id' => 'string',
        'cart_type' => 'string',
        'variant_id' => 'integer',
        'attribute_id' => 'integer'
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
