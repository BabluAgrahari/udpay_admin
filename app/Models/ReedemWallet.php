<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class ReedemWallet extends BaseModel
{
    use HasFactory;

    protected $table = 'redeemable_wallet';

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'user_num');
    }
}