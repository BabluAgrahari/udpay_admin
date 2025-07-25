<?php

namespace App\Models;

use App\Models\BaseModel;

class Unit extends BaseModel
{
    protected $table = 'uni_unit';
    
    protected $fillable = [
        'unit',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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

    /**
     * Search units by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('unit', 'like', '%' . $search . '%');
    }

    /**
     * Get units for dropdown
     */
    public function scopeForDropdown($query)
    {
        return $query->active()->select('id', 'unit')->orderBy('unit');
    }

    /**
     * Check if unit is active
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Get formatted unit name
     */
    public function getFormattedUnitAttribute()
    {
        return ucfirst(trim($this->unit));
    }
} 