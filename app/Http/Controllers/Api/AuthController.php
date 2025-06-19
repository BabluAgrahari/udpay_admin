<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'client_id'  => 'required|string',
            'secret_key' => 'required|string'
        ]);

        if ($validator->fails())
            return $this->validationRes($validator->messages());

        $user = User::with('Account')->where('client_id', $request->client_id)->where('secret_key', $request->secret_key)->first();

        if (empty($user))
            return $this->unauthorizedRes('Invalid Client Id/Secret Key.');

        $token = JWTAuth::fromUser($user);
        // \JWTAuth::setToken($token)->toUser();
        // $credentials = ['client_id' => $request->client_id, 'secret_key' => $request->secret_key];
        // $token = JWTAuth::attempt($credentials);
        if (!$token)
            return $this->unauthorizedRes('Something Went wrong Token not Generated.');

        return response()->json([
            'status' => true,
            'code'   => 200,
            'token'  => $token,
            'user'   => $user,
            'type'   => 'bearer'
        ]);
    }
}
