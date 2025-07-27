<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Observers\Timestamp;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;
use MongoDB\Laravel\Eloquent\Model;
<<<<<<< HEAD
=======
use App\Casts\ObjectIdCast;
use Illuminate\Support\Facades\Auth;
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ref_id',
        'user_nm',
        'alpha_num_uid',
        'first_name',
        'last_name',
        'dob',
        'mobile',
        'email',
        'password',
        'role',
<<<<<<< HEAD
=======
        'role_id',
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
        'restricted',
        'uflag',
        'royalty',
        'upgrade_date',
        'modified_on',
        'vercode',
        'vercode1',
        'mobile_otp',
        'epin',
        'planMem',
        'expire_otp_at',
        'isactive',
        //for panel user
        'address',
        'city',
        'state',
        'zip_code',
<<<<<<< HEAD
        'profile_pic'
=======
        'profile_pic',
        'gender'
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
    ];


    protected $casts = [
        'user_id' => 'string',
        'ref_id' => 'string',
        'user_nm' => 'string',
        'alpha_num_uid' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'dob' => 'date',
        'mobile' => 'string',
        'email' => 'string',
        'password' => 'hashed',
        'role' => 'string',
<<<<<<< HEAD
=======
        'role_id' => ObjectIdCast::class,
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
        'restricted' => 'integer',
        'uflag' => 'integer',
        'royalty' => 'integer',
        'upgrade_date' => 'date',
        'modified_on' => 'date',
        'vercode' => 'integer',
        'vercode1' => 'integer',
        'mobile_otp' => 'integer',
        'epin' => 'integer',
        'planMem' => 'integer',
        'expire_otp_at' => 'date',
        'isactive' => 'integer',
        //for panel user
        'address' => 'string',
        'city' => 'string',
        'state' => 'string',
        'zip_code' => 'string',
        'profile_pic' => 'string',
<<<<<<< HEAD
=======
        'gender' => 'string',
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
    ];
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
<<<<<<< HEAD
    
=======

>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42

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
<<<<<<< HEAD
=======

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
>>>>>>> 9cae8d43cbd99df28bc9af661b0d7feb4165cf42
}
