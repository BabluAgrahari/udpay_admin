<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Jobs\RegisterJob;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\LevelCount;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index($referral_id = null)
    {
        if (empty($referral_id)) {
            abort(500, 'Referral ID is required');
        }
        $data['user'] = User::where('user_num', $referral_id)->whereIn('role', ['distributor', 'customer'])->first();
        if (empty($data['user'])) {
            abort(500, 'Referral ID is not valid');
        }
        return view('Website.Distributor.signup', $data);
    }

    public function verifyPhone(Request $request)
    {
        //not allow 0 in start of mobile number
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|unique:users_lvl,mobile|numeric|not_in:0|regex:/^[1-9][0-9]{9}$/',
        ], [
            'mobile.required' => 'Phone number is required',
            'mobile.digits' => 'Phone number must be exactly 10 digits',
            'mobile.unique' => 'This phone number is already registered',
            'mobile.numeric' => 'Phone number must be a number',
            'mobile.not_in' => 'Phone number must not start with 0',
            'mobile.regex' => 'Phone number must not start with 0',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Session::put('signup_otp', $otp);
        Session::put('signup_mobile', $request->mobile);
        Session::put('otp_expires_at', now()->addMinutes(5));

        $smsService = new SmsService();
        $smsSent = $smsService->sendMessage('send_otp', $request->mobile, $otp);

        if (!$smsSent['status']) {
            return $this->failMsg($smsSent['msg']);
        }

        return $this->successMsg('OTP sent successfully to your phone number', ['otp' => $otp]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|numeric|not_in:0|regex:/^[1-9][0-9]{9}$/',
            'otp' => 'required|digits:6',
        ], [
            'mobile.numeric' => 'Phone number must be a number',
            'mobile.not_in' => 'Phone number must not start with 0',
            'mobile.regex' => 'Phone number must not start with 0',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        $storedOtp = Session::get('signup_otp');
        $storedPhone = Session::get('signup_mobile');
        $otpExpiresAt = Session::get('otp_expires_at');

        if (!$storedOtp || !$storedPhone || !$otpExpiresAt) {
            return $this->failMsg('OTP session expired. Please request a new OTP.');
        }

        if ($storedPhone !== $request->mobile) {
            return $this->failMsg('Phone number mismatch.');
        }

        if (now()->isAfter($otpExpiresAt)) {
            Session::forget(['signup_otp', 'signup_mobile', 'otp_expires_at']);
            return $this->failMsg('OTP has expired. Please request a new OTP.');
        }

        if ($storedOtp !== $request->otp) {
            return $this->failMsg('Invalid OTP. Please try again.');
        }

        Session::put('phone_verified', true);
        Session::put('verified_phone', $request->mobile);

        return $this->successMsg('Phone number verified successfully');
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|numeric|not_in:0|regex:/^[1-9][0-9]{9}$/',
        ], [
            'mobile.numeric' => 'Phone number must be a number',
            'mobile.not_in' => 'Phone number must not start with 0',
            'mobile.regex' => 'Phone number must not start with 0',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        Session::put('signup_otp', $otp);
        Session::put('signup_mobile', $request->mobile);
        Session::put('otp_expires_at', now()->addMinutes(5));

        $smsService = new SmsService();
        $smsSent = $smsService->sendMessage('send_otp', $request->mobile, $otp);

        if (!$smsSent['status']) {
            return $this->failMsg($smsSent['msg']);
        }

        return $this->successMsg('OTP resent successfully');
    }

    public function completeRegistration(Request $request)
    {
        if (!Session::get('phone_verified') || Session::get('verified_phone') !== $request->mobile) {
            return $this->failMsg('Please verify your phone number first.');
        }

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|unique:users_lvl,mobile|numeric|not_in:0|regex:/^[1-9][0-9]{9}$/',
            'full_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users_lvl,email',
            // 'password' => 'required|string|min:8',
            // 'confirm_password' => 'required|same:password',
            'referral_id' => 'required|string|max:255',
        ], [
            'mobile.numeric' => 'Phone number must be a number',
            'mobile.not_in' => 'Phone number must not start with 0',
            'mobile.regex' => 'Phone number must not start with 0',
            'mobile.required' => 'Phone number is required',
            'mobile.digits' => 'Phone number must be exactly 10 digits',
            'mobile.unique' => 'This phone number is already registered',
            'full_name.required' => 'Full name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'confirm_password.required' => 'Please confirm your password',
            'confirm_password.same' => 'Passwords do not match',
            'referral_id.required' => 'Referral ID is required',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        try {
            $existRefId = User::where('user_num', $request->referral_id)->first();
            if (empty($existRefId)) {
                return $this->failMsg('Referral ID is not valid');
            }

            DB::beginTransaction();
            $user = User::select('id', 'user_id')->orderBy('id', 'DESC')->first();
            $user_id = !empty($user->id) ? $user->user_id + 1 : 1;
            $user_num = rand(10000000, 99999999);  // should be unique

            //check user_num is unique then again generate user_num
            while (User::where('user_num', $user_num)->exists()) {
                $user_num = rand(10000000, 99999999);
            }

            if (User::where('user_id', $user_id)->exists()) {
                return $this->failMsg('User ID Already Exists, Please try again.');
            }

            if (User::where('user_num', $user_num)->exists()) {
                return $this->failMsg('User Num ID Already Exists, Please try again.');
            }

            $customer = new User();
            $customer->ref_id = $request->referral_id;
            $customer->user_id = $user_id;
            $customer->user_num = $user_num;
            $customer->alpha_num_uid = 'UNI' . $user_num;
            $customer->name = $request->full_name;
            $customer->email = $request->email;
            $customer->mobile = $request->mobile;
            // $customer->password = Hash::make($request->password);
            // $customer->pwd = $request->password;
            $customer->role = 'customer';
            $customer->type = 'customer';
            if ($customer->save()) {
                $res = $this->saveUserDetail(
                    $customer->ref_id,
                    $customer->user_num,
                    $user_id,
                    $customer->alpha_num_uid,
                    $customer->pwd,
                    $customer->email,
                    $customer->name,
                    $customer->mobile
                );
                if (!$res['status']) {
                    DB::rollBack();
                    return $this->failRes($res['msg'] ?? '');
                }
                DB::commit();
                return $this->successMsg('Account created successfully!', [
                    'alpha_num_id' => $customer->alpha_num_uid,
                    'username' => $customer->name
                ]);
            }
            Session::forget(['signup_otp', 'signup_mobile', 'otp_expires_at', 'phone_verified', 'verified_phone']);

            return $this->failMsg('Something went wrong. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failMsg($e->getMessage());
        }
    }

    private function saveUserDetail($refered_by, $user_num, $user_id, $userName, $password, $email, $name, $mobile)
    {
        $wallet = $this->createWallet($user_id, $user_num);
        if (!$wallet)
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in User Wallet'];

        $user = User::where('user_id', $user_id)->first();
        $user->uflag = 4;
        $user->save();

        $payload = ['user_id' => $user_id, 'unm' => $user_num, 'refered_by' => $refered_by, 'org' => ''];
        $this->CreateUserLevel($payload);
        // dispatch(new RegisterJob($payload));  // here run job queue  here

        $walletUpdate = Wallet::where('unm', $refered_by)->first();
        $existbp = $walletUpdate->bp ?? 0;
        $walletUpdate->bp = $existbp + 100;

        if ($walletUpdate->save()) {
            $sms = new SmsService;
            $msgSend = $sms->sendMessage('reg_msg', $mobile, '', 'UNI' . $user_num);
            if (!$msgSend)
                Log::warning('Something went wrong, SMS not sent - UserId', [$user_num]);

            // // for send mail by job queue
            // dispatch(new MailJob(['email' => $email, 'user_name' => 'UNI' . $user_nm, 'password' => $password, 'full_name' => $fname], 'sign_up'));

            return ['status' => true, 'msg' => 'Wallet Amount Updated Successfully!'];
        } else {
            Log::warning('Something went wrong, Wallet Amount not updated - UserId', [$user_num]);
            return ['status' => true, 'msg' => 'Something went wrong, Wallet Amount not updated'];
        }
    }

    private function createWallet($id, $unm)
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

    private function CreateUserLevel($payload)
    {
        $payload = (object) $payload;
        $insertLvl = $this->insertLvl($payload->unm);
        if (!$insertLvl) {
            Log::warning('Something went wrong, Not insert in User Lvl - UserId', [$payload->user_id]);
        } else {
            Log::info('Lvl created - UserId', [$payload->user_id]);
            $user = User::where('user_id', $payload->user_id)->first();
            $user->uflag = 5;
            $user->save();
        }
    }

    private function insertLvl($child_id)
    {
        $temp = $child_id;
        $lvl = 1;
        $refid = $child_id;
        while ($refid != "" || $refid != "0") {
            if ($refid >= 1) {

                $posid = User::select('ref_id')->where('user_num', $temp)->first();
                $refid = $posid->ref_id;

                $save = new LevelCount();
                $save->child_id = $child_id;
                $save->parent_id = $refid;
                $save->level = $lvl;
                $save->is_active = 0;
                if ($save->save()) {
                    $lvl = $lvl + 1;
                    $temp = $refid;
                    if ($temp == 0) {
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } //while 	   
    }

    public function welcome($id)
    {
        $user = User::where('alpha_num_uid', $id)->first();
        // print_r($user);die;
        if (empty($user)) {
            return redirect()->to('/');
        }

        $data['user'] = $user;

        return view('Website.Distributor.welcome_signup', $data);
    }
}
