<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id' => 'required|string',
            'password' => 'required|string',
        ], [
            'login_id.required' => 'User ID or Email is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return $this->validationRes($validator->errors());
        }

        try {
            $isEmail = filter_var($request->login_id, FILTER_VALIDATE_EMAIL);

            if ($isEmail) {
                $user = Customer::where('email', $request->login_id)->first();
            } else {
                $user = Customer::where('user_id', $request->login_id)->first();
            }

            if (!$user) {
                return $this->unauthorizedRes('Invalid User ID/Email or Password');
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->unauthorizedRes('Invalid User ID/Email or Password');
            }

            if (isset($user->status) && $user->status != 1) {
                return $this->unauthorizedRes('Your account is deactivated. Please contact support.');
            }

            $token = JWTAuth::fromUser($user);
            
            if (!$token) {
                return $this->unauthorizedRes('Something went wrong. Token not generated.');
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'type' => 'bearer'
            ]);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return $this->unauthorizedRes('Something went wrong. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Logout failed'
            ], 500);
        }
    }

    protected function validationRes($errors)
    {
        return response()->json([
            'status' => false,
            'code' => 422,
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422);
    }

    protected function unauthorizedRes($message)
    {
        return response()->json([
            'status' => false,
            'code' => 401,
            'message' => $message
        ], 401);
    }
}
