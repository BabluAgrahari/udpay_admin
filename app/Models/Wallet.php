<?php

namespace App\Models;

use App\Models\BaseModel;

class Wallet extends BaseModel
{
    protected $table = "wallet";

    public function Transaction()
    {
        return $this->hasMany(WalletTransition::class, 'user_id', 'userid')->orderBy('id','DESC')->limit('30');
    }
}
