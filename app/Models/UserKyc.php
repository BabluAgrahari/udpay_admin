<?php

namespace App\Models;

use App\Models\BaseModel;

class UserKyc extends BaseModel
{
    protected $table = 'user_kyc';
    protected $fillable = [
        'user_id',
        'mobile_no',
        'pan_no',
        'pan_front',
        'aadhar_no',
        'aadhar_front',
        'aadhar_back',
        'id_proof',
        'bank',
        'account_number',
        'ifsc_code',
        'branch',
        'bank_doc',
        'name',
        'gender',
        'dob',
        'state',
        'district',
        'locality',
        'pincode',
        'address',
        'work',
        'nominee',
        'relation',
        'kyc_flag',
        'bank_flag',
        'personal_flag',
        'status'
    ];

    protected $casts = [
        'user_id' => 'string',
        'mobile_no' => 'string',
        'pan_no' => 'string',
        'aadhar_no' => 'string',
        'aadhar_front' => 'string',
        'aadhar_back' => 'string',
        'pan_front' => 'string',
        'name' => 'string',
        'address' => 'string',
        'account_number' => 'string',
        'ifsc_code' => 'string',
        'branch' => 'string',
        'bank' => 'string',
        'id_proof' => 'string',
        'bank_doc' => 'string',
        'gender' => 'string',
        'dob' => 'date',
        'state' => 'string',
        'district' => 'string',
        'locality' => 'string',
        'pincode' => 'integer',
        'work' => 'string',
        'nominee' => 'string',
        'relation' => 'string',
        'kyc_flag' => 'integer',
        'bank_flag' => 'integer',
        'personal_flag' => 'integer',
        'status' => 'integer',
        'created_on' => 'datetime'
    ];

    /**
     * Get the user that owns the KYC.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    /**
     * Scope to get only active KYC records
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope to get KYC by user ID
     */
    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get verified KYC records
     */
    public function scopeVerified($query)
    {
        return $query->where('kyc_flag', 1);
    }

    /**
     * Scope to get pending KYC records
     */
    public function scopePending($query)
    {
        return $query->where('kyc_flag', 0);
    }

    /**
     * Scope to get KYC with personal details completed
     */
    public function scopePersonalCompleted($query)
    {
        return $query->where('personal_flag', 1);
    }

    /**
     * Scope to get KYC with bank details completed
     */
    public function scopeBankCompleted($query)
    {
        return $query->where('bank_flag', 1);
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->locality,
            $this->district,
            $this->state,
            $this->pincode
        ]);
        
        return implode(', ', $parts);
    }
}
