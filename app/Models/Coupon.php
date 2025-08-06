<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends BaseModel
{
    protected $table = 'uni_coupon';
    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_to',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1)
                    ->where(function($q) {
                        $q->whereNull('valid_from')
                          ->orWhere('valid_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('valid_to')
                          ->orWhere('valid_to', '>=', now());
                    });
    }

    public function isExpired()
    {
        return $this->valid_to && $this->valid_to < now();
    }

    public function isNotStarted()
    {
        return $this->valid_from && $this->valid_from > now();
    }

    public function isUsageLimitReached()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    public function canBeUsed()
    {
        return $this->status && 
               !$this->isExpired() && 
               !$this->isNotStarted() && 
               !$this->isUsageLimitReached();
    }
} 