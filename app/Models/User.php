<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Observers\Timestamp;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
// use App\Casts\ObjectIdCast;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'user_lvl';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function boot()
    {
        parent::boot();
        self::observe(Timestamp::class);
    }

    public function dFormat($date)
    {
        if (empty($date))
            return false;

        return date('d M,Y', $date);
    }

    public function Account()
    {
        return $this->hasOne(AccountSetting::class, 'user_id', '_id');
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class, '_id', 'merchant_id');
    }

    public function kyc()
    {
        return $this->hasOne(UserKyc::class, 'user_id', '_id');
    }

    public function roleTable()
    {
        return $this->hasOne(Role::class, '_id', 'role_id');
    }
    public function hasPermissionTo($permission)
    {
        if(Auth::user()->role=='supperadmin')
        return true;

        return in_array($permission, $this->roleTable->permissions ?? []);
    }
}
