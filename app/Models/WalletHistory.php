<?php

namespace App\Models;

use App\Casts\ObjectIdCast;
use App\Models\BaseModel;
use App\Observers\Timestamp;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\ObjectId;

class WalletHistory extends BaseModel
{
    protected $collection = 'wallet_history';
    
    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'remarks',
        'amount',
        'closing_amount',
        'source',
        'action_by'
    ];

    protected $casts = [
        'user_id' => ObjectIdCast::class,
        'wallet_id' => ObjectIdCast::class,
        'type' => 'string',
        'remarks' => 'string',
        'amount' => 'float',
        'closing_amount' => 'float',
        'source' => 'string',
        'action_by' => ObjectIdCast::class
    ];

    protected static function boot()
    {
        parent::boot();
        self::observe(Timestamp::class);
    }

    public function scopeAccess($query)
    {
        if (Auth::user()->role == 'supperadmin') {
            return $query->where('action_by', new ObjectId(Auth::id()));
        }else if(Auth::user()->role == 'admin'){
            return $query->where('action_by', new ObjectId(Auth::id()));
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function wallet()
    {
        return $this->belongsTo(UserWallet::class, 'wallet_id', '_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by', '_id');
    }
} 