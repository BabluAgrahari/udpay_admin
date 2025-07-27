<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class UserAddress extends Model
{
    use HasFactory;
     protected $table = 'uni_products';

    
    protected $fillable = [
        'user_add_name',
        'user_add_mobile',
        'alternate_mob',
        'user_add_1',
        'user_add_2',
        'user_zip_code',
        'land_mark',
        'user_city',
        'user_state',
        'user_country',
        'address_for',
        'address_type',
        'add_created_on',
        'add_from',
        'add_status'
    ];

}
