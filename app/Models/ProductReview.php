<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductReview extends BaseModel
{
    protected $table = 'users_review';

    protected $fillable = [
        'product_id',
        'uid',
        'rating',
        'review',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'string'
    ];

    /**
     * Get the product that owns the review
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the user that owns the review
     */
    public function user()
    {
        return $this->hasOne(User::class, 'user_id','uid');
    }

    /**
     * Scope for active reviews
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope for reviews by product
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope for reviews by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('uid', $userId);
    }
} 