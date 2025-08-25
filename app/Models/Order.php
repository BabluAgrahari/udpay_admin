<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\OrderToProduct;
use App\Models\User;
use App\Models\UserAddress;

class Order extends BaseModel
{
  
    protected $table='deals_order';

    protected $fillable = [
        'order_id',
        'uid',
        'total_amount',
        'total_net_amount',
        'status',
        'payment_method',
        'shipping_charges',
        'created_at',
        'updated_at'
    ];

    public function orderToProducts()
    {
        return $this->hasMany(OrderToProduct::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function shipping_address()
    {
        return $this->hasOne(UserAddress::class, 'id', 'address_id');
    }

    // Accessor for order_number
    public function getOrderNumberAttribute()
    {
        return $this->order_id ?? 'UNI' . $this->id;
    }

    // Accessor for payment_method
    public function getPaymentMethodAttribute()
    {
        return $this->payment_method ?? 'Wallet';
    }

    // Accessor for shipping_charges
    public function getShippingChargesAttribute()
    {
        return $this->shipping_charges ?? 0;
    }
} 