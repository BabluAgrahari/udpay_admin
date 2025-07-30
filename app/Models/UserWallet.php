<?php

namespace App\Models;

use App\Casts\ObjectIdCast;
use App\Models\BaseModel;
use App\Observers\Timestamp;

class UserWallet extends BaseModel
{
    protected $table = 'user_wallet';
    
    protected $fillable = [
        'user_id',
        'available_amount',
        'added_by'
    ];

    protected $casts = [
        'user_id' => ObjectIdCast::class,
        'available_amount' => 'float',
        'added_by' => ObjectIdCast::class
    ];  

    protected static function boot()
    {
        parent::boot();
        self::observe(Timestamp::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by', '_id');
    }

    public function walletHistory()
    {
        return $this->hasMany(WalletHistory::class, 'wallet_id', '_id');
    }
} 