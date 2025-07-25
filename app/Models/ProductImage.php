<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductImage extends BaseModel
{
    protected $table = 'uni_product_to_images';
    
    protected $fillable = [
        'product_id',
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that owns the image
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}