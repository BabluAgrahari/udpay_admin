<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransition extends Model
{
    protected $table = "wallet_transition";
    public $timestamps=false;
    use HasFactory;
}
