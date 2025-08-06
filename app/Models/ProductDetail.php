<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductDetail extends BaseModel
{
    protected $table = 'uni_product_details';
    
    protected $fillable = [
        'product_id',
        'details',
        'key_ings',
        'uses',
        'result',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that owns the detail
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Scope for active details
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }
}
