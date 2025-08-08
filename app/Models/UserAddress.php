<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends BaseModel
{
    use HasFactory;

    protected $table = 'user_address';

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
