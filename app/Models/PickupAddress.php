<?php

namespace App\Models;

use App\Models\BaseModel;

class PickupAddress extends BaseModel
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'location',
        'address',
        'city',
        'state',
        'pincode',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'pincode' => 'integer'
    ];

    
    
} 