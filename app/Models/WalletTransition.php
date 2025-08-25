<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class WalletTransition extends BaseModel
{
    protected $table = "wallet_transition";
    // public $timestamps=false;
    use HasFactory;
}
