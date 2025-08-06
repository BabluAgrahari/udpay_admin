<?php

namespace App\Models;

use App\Models\BaseModel;

class WalletHistory extends BaseModel
{
    protected $table = 'wallet_transition';

   



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
} 