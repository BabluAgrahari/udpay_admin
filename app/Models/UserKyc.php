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

    
}
