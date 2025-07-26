<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index()
    {
        return view('Website.signup');
    }

    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|unique:users_lvl,mobile',
        ], [
            'mobile.required' => 'Phone number is required',
            'mobile.digits' => 'Phone number must be exactly 10 digits',
            'mobile.unique' => 'This phone number is already registered',
        ]);

        if ($validator->fails()) {
            return $this->failMsg($validator->errors()->first());
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Session::put('signup_otp', $otp);
        Session::put('signup_mobile', $request->mobile);
        Session::put('otp_expires_at', now()->addMinutes(5));

        // $smsService = new SmsService();
        // $smsSent = $smsService->sendMessage('send_otp', $request->mobile, $otp);

        // if (!$smsSent['status']) {
        //     return $this->failMsg($smsSent['msg']);
        // }

        return $this->successMsg('OTP sent successfully to your phone number',['otp' => $otp]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
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
            'mobile' => 'required|digits:10',
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
            'mobile' => 'required|digits:10|unique:users_lvl,mobile',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users_lvl,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
            'referral_id' => 'required|string|max:255',
        ], [
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
            return $this->validateMsg($validator->errors()->first());
        }

        try {
            $existRefId = User::where('ref_id', $request->referral_id)->first();
            if (empty($existRefId)) {
                return $this->failMsg('Referral ID is not valid');
            }

            $user = User::select('id','user_id')->orderBy('id', 'DESC')->first();
            $user_id = !empty($user->id) ? $user->user_id + 1 : 1;
            $user_num = uniqCode(8);

            $customer = new User();
            $customer->ref_id = $request->referral_id;
            $customer->user_id = $user_id;
            $customer->user_num = $user_num;
            $customer->alpha_num_uid = 'UNI' . $user_id;
            $customer->name = $request->full_name;
            $customer->email = $request->email;
            $customer->mobile = $request->mobile;
            $customer->password = Hash::make($request->password);
            $customer->pwd = $request->password;
            if ($customer->save()) {
                // $smsService = new SmsService();
                // $smsService->sendMessage('reg_msg', $request->phone, $request->password, $request->full_name);
                return $this->successMsg('Account created successfully!');
            }
            Session::forget(['signup_otp', 'signup_mobile', 'otp_expires_at', 'phone_verified', 'verified_phone']);

            return $this->failMsg('Something went wrong. Please try again.');
        } catch (\Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }
}
