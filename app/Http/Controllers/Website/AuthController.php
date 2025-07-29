<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Send OTP to mobile number
     */
    public function sendOtp(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/'
            ], messages: [
                'mobile.regex' => 'Please enter a valid Indian mobile number starting with 6, 7, 8, or 9.'
            ]);

            if ($validator->fails()) {
                return $this->fail($validator->errors()->first());
            }

            $mobile = $request->mobile;

            if ($this->isMobileBlocked($mobile)) {
                return $this->fail('This mobile number is temporarily blocked. Please try again later.');
            }

            if (Session::has('otp_sent_time') && Session::has('mobile') && Session::get('mobile') === $mobile) {
                $lastSentTime = Session::get('otp_sent_time');
                $timeDiff = now()->diffInSeconds($lastSentTime);

                if ($timeDiff < 60) {
                    $remainingTime = 60 - $timeDiff;
                    return $this->fail("Please wait {$remainingTime} seconds before requesting another OTP.");
                }
            }

            $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

            $expiryTime = now()->addMinutes(5);

            Session::put('otp', $otp);
            Session::put('mobile', $mobile);
            Session::put('otp_expiry', $expiryTime);
            Session::put('otp_sent_time', now());
            Session::put('otp_attempts', 0);


            return $this->success('OTP sent successfully to +91-' . $mobile, ['otp' => $otp, 'mobile' => $mobile]);
        } catch (\Exception $e) {

            return $this->fail('Something went wrong. Please try again.');
        }
    }

    /**
     * Verify OTP and login user
     */
    public function verifyOtp(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'otp' => 'required|numeric|digits:4',
                'mobile' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/'
            ], [
                'mobile.regex' => 'Please enter a valid Indian mobile number starting with 6, 7, 8, or 9.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'msg' => $validator->errors()->first()
                ], 422);
            }

            $mobile = $request->mobile;
            $otp = $request->otp;

            // Check if OTP exists in session
            if (!Session::has('otp') || !Session::has('mobile') || !Session::has('otp_expiry')) {
                return response()->json([
                    'status' => false,
                    'msg' => 'OTP expired or not found. Please request new OTP.'
                ], 400);
            }

            // Verify mobile number matches
            if (Session::get('mobile') !== $mobile) {
                Log::warning('Mobile number mismatch during OTP verification', [
                    'session_mobile' => Session::get('mobile'),
                    'request_mobile' => $mobile,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'status' => false,
                    'msg' => 'Mobile number mismatch.'
                ], 400);
            }

            // Check OTP expiry
            if (now()->isAfter(Session::get('otp_expiry'))) {
                // Clear expired session data
                Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);
                return response()->json([
                    'status' => false,
                    'msg' => 'OTP has expired. Please request new OTP.'
                ], 400);
            }

            // Track OTP attempts
            $attempts = Session::get('otp_attempts', 0) + 1;
            Session::put('otp_attempts', $attempts);

            // Block after 5 failed attempts
            if ($attempts > 5) {
                Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);
                $this->blockMobile($mobile);

                Log::warning('Mobile number blocked due to multiple failed OTP attempts', [
                    'mobile' => $mobile,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'status' => false,
                    'msg' => 'Too many failed attempts. Please try again after 1 hour.'
                ], 429);
            }

            // Verify OTP
            if (Session::get('otp') !== $otp) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid OTP. Please try again. (' . (5 - $attempts) . ' attempts remaining)'
                ], 400);
            }

            // OTP is valid, now handle user login/registration
            $user = User::where('mobile', $mobile)->where('role', 'customer')->first();

            if (!$user) {
                // First time user - create new user
                $user = $this->createNewUser($mobile);

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'msg' => 'Failed to create user account.'
                    ], 500);
                }

                Log::info('New user created via OTP', [
                    'mobile' => $mobile,
                    'user_id' => $user->_id
                ]);
            }

            // Login the user
            Auth::login($user);

            // Clear OTP session data
            Session::forget(['otp', 'mobile', 'otp_expiry', 'otp_sent_time', 'otp_attempts']);

            Log::info('User logged in successfully', [
                'user_id' => $user->_id,
                'mobile' => $mobile,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Login successful!',
                'user' => [
                    'id' => $user->_id,
                    'name' => trim($user->first_name . ' ' . $user->last_name) ?: 'User',
                    'mobile' => $user->mobile,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying OTP', [
                'mobile' => $request->mobile ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'msg' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            Auth::logout();
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

    /**
     * Check if user is logged in
     */
    public function checkAuth()
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
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

    /**
     * Create new user account
     */
    private function createNewUser($mobile)
    {
        try {
            $user = new User();
            $user->mobile = $mobile;
            $user->role = 'customer';
            $user->isactive = 1;
            $user->first_name = 'User';
            $user->last_name = '';
            $user->email = $mobile . '@temp.com';
            $user->password = Hash::make(Str::random(16));

            return $user->save() ? $user : null;
        } catch (\Exception $e) {
            Log::error('Error creating new user', [
                'mobile' => $mobile,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if mobile number is blocked
     */
    private function isMobileBlocked($mobile)
    {
        // This is a simple implementation using session
        // In production, you might want to use Redis or database
        $blockedMobiles = Session::get('blocked_mobiles', []);
        $blockedMobile = $blockedMobiles[$mobile] ?? null;

        if ($blockedMobile && now()->isBefore($blockedMobile['blocked_until'])) {
            return true;
        }

        // Remove expired blocks
        if ($blockedMobile && now()->isAfter($blockedMobile['blocked_until'])) {
            unset($blockedMobiles[$mobile]);
            Session::put('blocked_mobiles', $blockedMobiles);
        }

        return false;
    }

    /**
     * Block mobile number
     */
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
