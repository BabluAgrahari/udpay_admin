<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SigninController extends Controller
{
    public function index()
    {
        return view('Website.signin');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_id' => 'required|string',
            'password' => 'required|string|min:1',
        ], [
            'login_id.required' => 'User ID or Email is required',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return $this->validateMsg($validator->errors());
        }

        try {
            $isEmail = filter_var($request->login_id, FILTER_VALIDATE_EMAIL);

            if ($isEmail) {
                $user = User::where('email', $request->login_id)->first();
            } else {
                $user = User::where('user_num', $request->login_id)->first();
            }

            if (!$user) {
                return $this->failMsg('Invalid User ID/Email or Password');
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->failMsg('Invalid User ID/Email or Password');
            }

            if (isset($user->status) && $user->status != 1) {
                return $this->failMsg('Your account is deactivated. Please contact support.');
            }
            Auth::login($user);
            if (Auth::check()) {
                return $this->successMsg('Login successful!', ['redirect' => url('/distributor/dashboard')]);
            } else {
                return $this->failMsg('Something went wrong. Please try again.');
            }
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage() ?? 'Something went wrong. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('signin')->with('success', 'You have been logged out successfully.');
    }
}
