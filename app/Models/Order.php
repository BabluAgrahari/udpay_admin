<?php

namespace App\Models;

use App\Models\BaseModel;

class Order extends BaseModel
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'order_date',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'shipping_address',
        'billing_address',
        'notes',
        'status'
    ];

    protected $casts = [
        'order_number' => 'string',
        'customer_name' => 'string',
        'customer_email' => 'string',
        'customer_phone' => 'string',
        'customer_address' => 'string',
        'order_date' => 'datetime',
        'total_amount' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'final_amount' => 'float',
        'payment_status' => 'string',
        'order_status' => 'string',
        'payment_method' => 'string',
        'shipping_address' => 'string',
        'billing_address' => 'string',
        'notes' => 'string',
        'status' => 'integer'
    ];

    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeOrderStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

} 