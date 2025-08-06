<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutTransaction extends BaseModel   
{
    use HasFactory;

    protected $table = 'payout_transition';
  
}