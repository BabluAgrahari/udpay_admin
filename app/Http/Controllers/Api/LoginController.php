<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Validation\CheckAvailability;
use App\Http\Validation\RegisterValidation;
use App\Jobs\MailJob;
use App\Jobs\RegisterJob;
use App\Mail\OTPMail;
use App\Models\BinaryData;
use App\Models\LevelCount;
use App\Models\MemberBelow;
use App\Models\MemberBv;
use App\Models\Org;
use App\Models\Orgap;
use App\Models\User;
use App\Models\UsersLvlS1;
use App\Models\Wallet;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|string',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            UsersLvlS1::where('mobile', $request->mobile)->delete();

            $otp = rand(111111, 999999);

            $sms = new SmsService();
            $res = $sms->sendMessage('reg_otp', $request->mobile, $otp, '', '');
            if (empty($res['status'])) {
                return $this->failRes($res['msg']);
            }

            $save = UsersLvlS1::find($request->id);
            $save->mobile_otp = md5($otp);
            $save->otp_expired = strtotime(Carbon::now()->addMinutes(config('global.otpExpired')));
            if ($save->save()) {
                $existUser = User::where('mobile', $request->mobile)->first();
                return $this->recordResMsg(
                    [
                        'expire_time' => $save->otp_expired,
                        'time' => config('global.otpExpired'),
                        'new_user' => $existUser ? false : true
                    ],
                    'OTP Sent on ' . $request->mobile
                );
            }
            return $this->failRes('Something Went Wrong, OTP not Sent.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|numeric|digits:6',
                'mobile_no' => 'required|digits:10',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $userLvl = UsersLvlS1::where('mobile', $request->mobile_no)->first();
            if (empty($userLvl))
                return $this->failRes('Invalid Mobile No.');

            if (strtotime(now()) >= $userLvl->otp_expired)
                return $this->failRes('OTP is Expired.');

            if ($userLvl->mobile_otp != md5($request->otp))
                return $this->failRes('OTP Mismatch, OTP not Verified.');

            $save = User::where('mobile', $userLvl->mobile)->first();
            if (empty($save)) {
                $user = User::select('id', 'user_id')->orderBy('id', 'DESC')->first();
                $user_id = !empty($user->user_id) ? $user->user_id + 1 : 1;
                $user_nm = uniqCode(8);

                $save = new User();
                $save->refid = $userLvl->refid;
                $save->user_id = $user_id;
                $save->user_num = $user_nm;
                $save->alpha_num_uid = 'UNI' . $user_id;
                $save->mobile = $userLvl->mobile;
                $save->isactive = 1;
                if (!$save->save())
                    return $this->failRes('Something Went wrong in Otp Verification process.');
            }

            UsersLvlS1::where('mobile', $userLvl->mobile)->delete();

            $res = $this->saveUserDetail($userLvl->refid, $userLvl->user_nm, $user_id, $save->alpha_num_uid, $save->pwd, $save->email, $save->fname, $save->mobile);
            if (!$res['status']) {
                return $this->failRes($res['msg'] ?? '');
            }

            $user = $save->alpha_num_uid ?? '';
            $telegram = 'https://t.me/unipayofficial';
            return $this->recordResMsg(['alpha_num_id' => 'UNI' . $user_nm, 'username' => $save->fname], 'Otp Verified, Registered Successfully!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function store(RegisterValidation $request)
    {
        try {
            // return $this->failRes('Under Maintinance ');
            // return $this->failRes('Something Went Wrong, User not registered. Please try after 30 minutes.');
            // die;
            // dispatch(new MailJob(['email' => 'agraharibablu99@gmail.com', 'password' => '123456', 'full_name' => 'demo'], 'sign_up'));

            $user = User::select('id')->orderBy('id', 'DESC')->first();
            $user_id = !empty($user->id) ? $user->user_id + 1 : 1;
            $user_nm = uniqCode(8);

            /* if ($request->refered_by < 1100 || $request->refered_by == 1359)
                 return $this->failRes('Referal Not Exist.');*/

            $count = User::select('user_id')->where('user_nm', $request->refered_by)->count();
            if ($count <= 0)
                return $this->failRes('Referal Not Exist.');

            $save = new UsersLvlS1();
            $save->refid = $request->refered_by;
            $save->user_id = $user_id;
            $save->fname = $request->full_name;
            $save->mobile = $request->mobile;
            $save->email = $request->email;
            $save->vercode1 = md5($user_id) . '.' . md5(date('Y-m-d')) . '.' . $user_nm;
            // $save->inorg         = strtoupper($request->org);
            $save->doj = date('Y-m-d H:i:s');
            $save->alpha_num_uid = 'UNI' . $user_id;
            $save->user_nm = $user_nm;
            $save->password = Hash::make($request->password);
            $save->pwd = $request->password;
            $save->isactive = 0;

            if (!$save->save())
                return $this->failRes('Something Went Wrong, User not registered');

            // for send otp
            $payload = [
                'id' => $save->id,
                'otp_type' => $request->otp_type,
                'fname' => $save->fname,
                'mobile' => $save->mobile,
                'email' => $save->email
            ];
            $genrateOtp = $this->generateOtp($payload);
            if (!$genrateOtp)
                return $this->failRes('OTP not Sent.');

            $msg = strtolower($request->otp_type) == 'm' ? '+91********' . substr($request->mobile, -2) : substr_replace($request->email, '********', 2, -12);
            $user = UsersLvlS1::select('otp_expired')->find($save->id);
            return $this->recordResMsg(['key' => $save->vercode1, 'expire_time' => (int) $user->otp_expired ?? '', 'time' => config('global.otpExpired')], 'OTP Sent on ' . $msg);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function userAvailability(CheckAvailability $request)
    {
        try {
            $phoneFlag = $emailFlag = true;
            $uidFlag = false;

            if (empty($request->uid) && empty($request->phone) && empty($request->email))
                return $this->failRes('Please Provide at least one parameter.');

            // check user id here
            if (!empty($request->uid)) {
                $uidCount = User::where('user_nm', $request->uid)->count();
                $uidFlag = false;
                if ($uidCount > 0)
                    $uidFlag = true;
            }

            // check phone here
            if (!empty($request->phone)) {
                $phoeCount = User::where('mobile', $request->phone)->count();
                $phoneFlag = true;
                if ($phoeCount > 0)
                    $phoneFlag = false;
            }

            // check email here
            if (!empty($request->email)) {
                $emailCount = User::where('email', $request->email)->count();
                $emailFlag = true;
                if ($emailCount > 0)
                    $emailFlag = false;
            }

            $query = User::select('user_id', 'fname', 'mobile');
            if (!empty($request->uid)) {
                $query->where('user_nm', $request->uid);
            }
            if (!empty($request->phone))
                $query->where('mobile', $request->phone);
            if (!empty($request->email))
                $query->where('email', $request->email);

            $user = $query->first();

            // if ($uidFlag && $phoneFlag && $emailFlag) {
            //     if (!$uidFlag) {
            //         return $this->failRes('Referal Id Not Exist.');
            //     }
            //     return $this->failRes('User Not Exist.');
            // }

            $array = [
                'uid' => $uidFlag,
                'phone' => $phoneFlag,
                'email' => $emailFlag,
            ];
            if ((!empty($user)) && ($uidFlag || !$phoneFlag || !$emailFlag)) {
                $array['user_data'] = [
                    'uid' => $user->user_id ?? '',
                    'name' => $user->fname ?? '',
                    'mobile' => $user->mobile ?? ''
                ];
                $msg = 'User Not Exist.';
            }
            $msg = 'User Not Existt.';
            if ($uidFlag) {
                $msg = 'Referal Id Found.';
            } elseif (!$phoneFlag || !$emailFlag) {
                $msg = 'User Exist.';
                return response()->json([
                    'status' => false,
                    'code' => 403,
                    'msg' => $msg,
                    'record' => $array
                ], 403);
            } elseif ($phoneFlag || $emailFlag) {
                $msg = 'User Not Exist.';
                if (!$uidFlag && $request->type == 'uid') {
                    return $this->failRes('Referal Id Not Found.');
                }
            }
            return $this->recordResMsg($array, $msg);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp_type' => 'required',
                'key' => 'required',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $user = UsersLvlS1::where('vercode1', $request->key)->first();
            if (empty($user))
                return $this->failRes('Invalid Key.');

            // for send otp
            $payload = [
                'id' => $user->id,
                'otp_type' => $request->otp_type,
                'fname' => $user->fname,
                'mobile' => $user->mobile,
                'email' => $user->email
            ];
            $genrateOtp = $this->generateOtp($payload);
            if (!$genrateOtp)
                return $this->failRes('OTP not Sent.');

            $msg = strtolower($request->otp_type) == 'm' ? '+91********' . substr($user->mobile, -2) : substr_replace($user->email, '********', 2, -12);

            $user = UsersLvlS1::select('otp_expired', 'vercode1')->find($user->id);
            return $this->recordResMsg(['key' => $user->vercode1, 'expire_time' => (int) $user->otp_expired ?? '', 'time' => config('global.otpExpired')], 'OTP Sent on ' . $msg);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function saveUserDetail($refered_by, $user_nm, $user_id, $userName, $password, $email, $fname, $mobile)
    {
        $wallet = $this->insertWallet($user_id, $user_nm);
        if (!$wallet) {
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in User Wallet'];
        } else {
            $user = User::where('user_id', $user_id)->first();
            $user->uflag = 4;
            $user->save();
        }

        $payload = ['user_id' => $user_id, 'unm' => $user_nm, 'refered_by' => $refered_by, 'org' => $org = ''];
        dispatch(new RegisterJob($payload));  // here run job queue  hereeeee

        $walletUpdate = Wallet::where('unm', $refered_by)->first();
        $existbp = $walletUpdate->bp ?? 0;
        $walletUpdate->bp = $existbp + 100;

        if ($walletUpdate->save()) {
            $sms = new SMSSender;
            $msgSend = $sms->sendMessage('reg_msg', $mobile, '', 'UNI' . $user_nm, $password);
            if (!$msgSend)
                Log::warning('Something went wrong, SMS not sent - UserId', [$user_nm]);

            // for send mail by job queue
            dispatch(new MailJob(['email' => $email, 'user_name' => 'UNI' . $user_nm, 'password' => $password, 'full_name' => $fname], 'sign_up'));

            return ['status' => true, 'msg' => 'Wallet Amount Updated Successfully!'];
        } else {
            Log::warning('Something went wrong, Wallet Amount not updated - UserId', [$user_nm]);
            return ['status' => true, 'msg' => 'Something went wrong, Wallet Amount not updated'];
        }
    }

   

    private function insertWallet($id, $unm)
    {
        $save = new Wallet();
        $save->userid = $id;
        $save->unm = $unm;
        $save->bp = 100;
        $save->amount = 0;
        $save->earning = 0;
        $save->sp = 0;
        $save->unicash = 0;
        $save->sec_val = 0;
        if ($save->save())
            return true;

        return false;
    }

    function test1()
    {
        $email = 'bhanujangid803@gmail.com';
        $userName = 'Bhumika sharma';
        $password = '123456';
        $fname = 'Bhumika';
        $mailData = [
            'email' => $email,
            'user_name' => $userName,
            'password' => $password,
            'full_name' => $fname,
            'sub' => 'Verification Code',
        ];

        $m = Mail::to($email)->send(new OTPMail($mailData));
        if ($m)
            return true;

        return false;
        // dispatch(new MailJob(['email' => $email, 'user_name' => $userName,'password' => $password, 'full_name' => $fname], 'sign_up'));
    }
}
