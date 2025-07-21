<?php

namespace App\Models;

use App\Models\BaseModel;

class Order extends BaseModel
{
    protected $fillable = [
        'order_number',
        'order_date',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'delivery_address',
        'notes',
        'status',
        'value',
        'weight',
        'pickup_address_id',
        'return_address_id',
        'rto_address_id',
        'products',
        'dimensions',
    ];

    protected $casts = [
        'order_number' => 'string',
        'order_date' => 'timestamp',
        'total_amount' => 'float',
        'tax_amount' => 'float',
        'discount_amount' => 'float',
        'final_amount' => 'float',
        'payment_status' => 'string',
        'order_status' => 'string',
        'payment_method' => 'string',
       
        'notes' => 'string',
        'status' => 'integer',
        'cod_value' => 'float',
        'weight' => 'float',
        'pickup_address_id' => 'string',
        'return_address_id' => 'string',
        'rto_address_id' => 'string',
       
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