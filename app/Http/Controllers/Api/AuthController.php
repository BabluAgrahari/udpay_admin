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

class AuthController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            UsersLvlS1::where('mobile', $request->mobile_no)->delete();

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
                $existUser = User::where('mobile', $request->mobile_no)->first();
                return $this->recordResMsg(
                    [
                        'expire_time' => $save->otp_expired,
                        'time' => config('global.otpExpired'),
                        'new_user' => $existUser ? false : true
                    ],
                    'OTP Sent on ' . $request->mobile_no
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
                'ref_id' => 'required|string',
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
                $save->refid = $request->ref_id ?? null;
                $save->user_id = $user_id;
                $save->user_num = $user_nm;
                $save->alpha_num_uid = 'UNI' . $user_id;
                $save->mobile = $userLvl->mobile;
                $save->isactive = 1;
                if (!$save->save())
                    return $this->failRes('Something Went wrong in Otp Verification process.');
            }

            UsersLvlS1::where('mobile', $userLvl->mobile)->delete();

            if (!empty($request->ref_id)) {
                $res = $this->saveUserDetail($request->ref_id, $userLvl->user_nm, $user_id);
                if (!$res['status']) {
                    return $this->failRes($res['msg'] ?? '');
                }
            }

            return $this->recordResMsg(['alpha_num_id' => 'UNI' . $user_nm], 'Otp Verified, Registered Successfully!');
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
                'mobile_no' => 'required',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $otp = rand(111111, 999999);
            $sms = new SmsService();
            $res = $sms->sendMessage('reg_otp', $request->mobile_no, $otp, '', '');
            if (empty($res['status'])) {
                return $this->failRes($res['msg'] ?? '');
            }

            $save = UsersLvlS1::where('mobile', $request->mobile_no)->first();
            $save->mobile_otp = md5($otp);
            $save->otp_expired = strtotime(Carbon::now()->addMinutes(config('global.otpExpired')));
            if ($save->save()) {
                return $this->recordResMsg(['expire_time' => $save->otp_expired, 'time' => config('global.otpExpired')], 'OTP Sent on ' . $request->mobile_no);
            }
            return $this->failRes('Something Went Wrong, OTP not Sent.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function saveUserDetail($refered_by, $user_nm, $user_id)
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
            // $sms = new SMSSender;
            // $msgSend = $sms->sendMessage('reg_msg', $mobile, '', 'UNI' . $user_nm, $password);
            // if (!$msgSend)
            //     Log::warning('Something went wrong, SMS not sent - UserId', [$user_nm]);

            // for send mail by job queue
            //  dispatch(new MailJob(['email' => $email, 'user_name' => 'UNI' . $user_nm, 'password' => $password, 'full_name' => $fname], 'sign_up'));

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
}
