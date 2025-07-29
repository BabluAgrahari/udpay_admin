<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/'
            ], messages: [
                'mobile.regex' => 'Please enter a valid Indian mobile number starting with 6, 7, 8, or 9.'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }
            $mobile = $request->mobile;

            if ($this->isMobileBlocked($mobile)) {
                return $this->failMsg('This mobile number is temporarily blocked. Please try again later.');
            }

            if (Session::has('otp_sent_time') && Session::has('mobile') && Session::get('mobile') === $mobile) {
                $lastSentTime = Session::get('otp_sent_time');
                $timeDiff = now()->diffInSeconds($lastSentTime);

                if ($timeDiff < 60) {
                    $remainingTime = 60 - $timeDiff;
                    return $this->failMsg("Please wait {$remainingTime} seconds before requesting another OTP.");
                }
            }

            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $expiryTime = now()->addMinutes(5);

            // $smsService = new SmsService();
            // $res = $smsService->sendOtp($mobile, $otp);
            // if ($res['status'] == false) {
            //     return $this->failMsg($res['msg']);
            // }

            Session::put('otp', $otp);
            Session::put('mobile', $mobile);
            Session::put('otp_expiry', $expiryTime);
            Session::put('otp_sent_time', now());
            Session::put('otp_attempts', 0);

            return $this->successMsg('OTP sent successfully to +91-' . $mobile, ['otp' => $otp, 'mobile' => $mobile]);
        } catch (\Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|numeric|digits:6',
                'mobile' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/'
            ], [
                'mobile.regex' => 'Please enter a valid Indian mobile number starting with 6, 7, 8, or 9.'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $mobile = $request->mobile;
            $otp = $request->otp;

            if (!Session::has('otp') || !Session::has('mobile') || !Session::has('otp_expiry')) {
                return $this->failMsg('OTP expired or not found. Please request new OTP.');
            }

            if (Session::get('mobile') !== $mobile) {
                return $this->failMsg('Mobile number mismatch.');
            }

            if (now()->isAfter(Session::get('otp_expiry'))) {
                Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);
                return $this->failMsg('OTP has expired. Please request new OTP.');
            }

            $attempts = Session::get('otp_attempts', 0) + 1;
            Session::put('otp_attempts', $attempts);

            if ($attempts > 5) {
                Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);
                $this->blockMobile($mobile);
                return $this->failMsg('Too many failed attempts. Please try again after 1 hour.');
            }

            if (Session::get('otp') !== $otp) {
                return $this->failMsg('Invalid OTP. Please try again. (' . (5 - $attempts) . ' attempts remaining)');
            }

            $user = Customer::where('mobile', $mobile)->first();

            if (!$user) {
                $user = $this->createNewUser($mobile);

                if (!$user) {
                    return $this->failMsg('Failed to create user account.');
                }
            }

            Auth::guard('web')->login($user);

            Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);

            return $this->successMsg('Login successful!', ['user' => $user]);
        } catch (\Exception $e) {
            return $this->failMsg('Something went wrong. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::guard('web')->user();

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($user) {
                Log::info('User logged out', [
                    'user_id' => $user->_id,
                    'mobile' => $user->mobile,
                    'ip' => $request->ip()
                ]);
            }

            return response()->json([
                'status' => true,
                'msg' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error during logout', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong during logout.'
            ], 500);
        }
    }

    public function checkAuth()
    {
        try {
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                return response()->json([
                    'status' => true,
                    'logged_in' => true,
                    'user' => [
                        'id' => $user->_id,
                        'name' => trim($user->first_name . ' ' . $user->last_name) ?: 'User',
                        'mobile' => $user->mobile,
                        'email' => $user->email
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'logged_in' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking auth status', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong.'
            ], 500);
        }
    }

    private function createNewUser($mobile)
    {
        $user = Customer::select('id')->orderBy('id', 'DESC')->first();
        $user_id = !empty($user->id) ? $user->user_id + 1 : 1;
        $user_nm = uniqCode(8);

        $customer = new Customer();
        $customer->mobile = $mobile;
        $customer->usre_id = $user_id;
        $customer->user_num = $user_nm;
        $customer->alpha_num_uid = 'UNI'.$user_nm;
        $customer->isactive = 1;
        return $customer->save() ? $customer : null;
    }

    private function isMobileBlocked($mobile)
    {
        $blockedMobiles = Session::get('blocked_mobiles', []);
        $blockedMobile = $blockedMobiles[$mobile] ?? null;

        if ($blockedMobile && now()->isBefore($blockedMobile['blocked_until'])) {
            return true;
        }

        if ($blockedMobile && now()->isAfter($blockedMobile['blocked_until'])) {
            unset($blockedMobiles[$mobile]);
            Session::put('blocked_mobiles', $blockedMobiles);
        }

        return false;
    }

    private function blockMobile($mobile)
    {
        $blockedMobiles = Session::get('blocked_mobiles', []);
        $blockedMobiles[$mobile] = [
            'blocked_at' => now(),
            'blocked_until' => now()->addHour()
        ];
        Session::put('blocked_mobiles', $blockedMobiles);
    }
}
