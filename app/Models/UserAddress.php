<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class UserAddress extends Model
{
    use HasFactory;
    
    protected $table = 'user_address';

    protected $fillable = [
        'user_id',
        'user_add_name',
        'user_add_mobile',
        'alternate_mob',
        'user_add_1',
        'user_add_2',
        'user_zip_code',
        'land_mark',
        'user_state',
        'user_city',
        'user_country',
        'address_for',
        'address_type', // billing or shipping
        'add_status', // 0,1
        'is_default', // 0,1
    ];

    protected $casts = [
        'add_status' => 'boolean',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the address
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get only active addresses
     */
    public function scopeActive($query)
    {
        return $query->where('add_status', 1);
    }

    /**
     * Scope to get only default addresses
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }

    /**
     * Scope to get addresses by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('address_type', $type);
    }

    /**
     * Scope to get addresses by purpose (billing/shipping)
     */
    public function scopeByPurpose($query, $purpose)
    {
        return $query->where('address_for', $purpose);
    }

    /**
     * Get full address as string
     */
    public function getFullAddressAttribute()
    {
        $address = $this->user_add_1;
        
        if ($this->user_add_2) {
            $address .= ', ' . $this->user_add_2;
        }
        
        if ($this->land_mark) {
            $address .= ', ' . $this->land_mark;
        }
        
        $address .= ', ' . $this->user_city . ', ' . $this->user_state . ' - ' . $this->user_zip_code;
        
        if ($this->user_country) {
            $address .= ', ' . $this->user_country;
        }
        
        return $address;
    }

    /**
     * Get formatted address for display
     */
    public function getFormattedAddressAttribute()
    {
        $lines = [];
        
        if ($this->user_add_name) {
            $lines[] = $this->user_add_name;
        }
        
        if ($this->user_add_1) {
            $lines[] = $this->user_add_1;
        }
        
        if ($this->user_add_2) {
            $lines[] = $this->user_add_2;
        }
        
        if ($this->land_mark) {
            $lines[] = 'Landmark: ' . $this->land_mark;
        }
        
        $cityState = $this->user_city . ', ' . $this->user_state . ' - ' . $this->user_zip_code;
        $lines[] = $cityState;
        
        if ($this->user_country) {
            $lines[] = $this->user_country;
        }
        
        if ($this->user_add_mobile) {
            $lines[] = 'Phone: ' . $this->user_add_mobile;
        }
        
        return implode("\n", $lines);
    }

    /**
     * Check if address is default
     */
    public function isDefault()
    {
        return $this->is_default == 1;
    }

    /**
     * Check if address is active
     */
    public function isActive()
    {
        return $this->add_status == 1;
    }

    /**
     * Set as default address
     */
    public function setAsDefault()
    {
        // Remove default from other addresses of the same user
        static::where('user_id', $this->user_id)
              ->where('id', '!=', $this->id)
              ->update(['is_default' => 0]);
        
        $this->is_default = 1;
        $this->save();
        
        return $this;
    }

    /**
     * Soft delete the address
     */
    public function softDelete()
    {
        $this->add_status = 0;
        $this->is_default = 0;
        $this->save();
        
        return $this;
    }

    /**
     * Restore the address
     */
    public function restore()
    {
        $this->add_status = 1;
        $this->save();
        
        return $this;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new address, if it's the first one, make it default
        static::creating(function ($address) {
            $existingAddresses = static::where('user_id', $address->user_id)
                                     ->where('add_status', 1)
                                     ->count();
            
            if ($existingAddresses === 0) {
                $address->is_default = 1;
            }
        });
    }
}
