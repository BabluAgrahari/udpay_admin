<?php

namespace App\Models;

use App\Models\BaseModel;

class Unit extends BaseModel
{
    protected $fillable = [
        'unit',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Get active units
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get inactive units
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
} 