<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = "wallet";
    public $timestamps = false;


    public function Transaction()
    {
        return $this->hasMany(WalletTransition::class, 'user_id', 'userid')->orderBy('id','DESC')->limit('30');
    }
}
