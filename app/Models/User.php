<?php

namespace App\Models;

use App\Mail\DemoMail;
use App\Models\User_kyc;
use App\Models\Wallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Mail;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users_lvl';
    public $timestamps = false;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Wallet()
    {
        return $this->hasOne(Wallet::class, 'userid', 'user_id');
    }

    public function UserKyc()
    {
        return $this->hasOne(UserKyc::class, 'userId', 'user_id');
    }

    public function Royalty()
    {
        return $this->hasOne(Royalty::class, 'userid', 'user_id');
    }

    function getParentId1($mid = '')
    {
        $count = Binary_data::where('userid', $mid)->get()->count();
        // echo $this->db->last_query();
        if ($count == 1) {
            $posid = Binary_data::where('userid', $mid)->first();
            return $posid->posid;
        } else {
            return 0;
        }
    }

    function getPositionParent1($mid = '')
    {
        $count = Binary_data::where('userid', $mid)->get()->count();

        if ($count == 1) {
            $position = Binary_data::where('userid', $mid)->first();
            return $position->position;
        } else {
            return 0;
        }
    }

    function getTreeChildId1($parentid = '', $position = '')
    {
        $count = binary_data::where('posid', $parentid)->where('position', $position)->get()->count();
        if ($count == 1) {
            $res = binary_data::where('posid', $parentid)->where('position', $position)->first();
            return $cid = $res->userid;
        } else {
            return -1;
        }
    }

    function getLastChildOfLR1($refid = '', $position = '')
    {
        $parentid = $refid;
        $childid = self::getTreeChildId1($parentid, $position);
        if ($childid != '-1') {
            $mid = $childid;
        } else {
            $mid = $parentid;
        }
        $flag = 0;
        while ($mid != '' || $mid != '0') {
            $count = Binary_data::where('userid', $mid)->get()->count();
            if ($count == 1) {
                $nextchildid = self::getTreeChildId1($mid, $position);
                if ($nextchildid == '-1') {
                    $flag = 1;
                    break;
                } else {
                    $mid = $nextchildid;
                }
            }  // if
            else
                break;
        }  // while
        return $mid;
    }

    function add_wallet($key, $uid, $payout)
    {
        $res = Wallet::where('userid', $uid)->first();
        if (!empty($res)) {
            if ($key == 1) {
                $save = Wallet::where('userid', $uid)->update(array('earning' => $res->earning + $payout));
            } elseif ($key == 2) {
                $save = Wallet::where('userid', $uid)->update(array('sp' => $res->sp + $payout));
            } elseif ($key == 3) {
                $save = Wallet::where('userid', $uid)->update(array('bp' => $res->bp + $payout));
            } elseif ($key == 4) {
                $save = Wallet::where('userid', $uid)->update(array('unicash' => $res->unicash + $payout));
            } elseif ($key == 5) {
                $save = Wallet::where('userid', $uid)->update(array('amount' => $res->amount + $payout));
            }
            if ($save) {
                $msg = true;
            } else {
                $msg = $save->save();
            }
            return $msg;
        }
    }
}
