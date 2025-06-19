<?php

namespace App\Http\Middleware;

use App\Traits\Response;
use Closure;
use Exception;
use App\Models\AccountSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class IPValidateMiddleware
{
    use Response;

    public function handle(Request $request, Closure $next)
    {

        Log::info('Server IP Address', [$request->ip()]);

        try {
            $ip = $request->ip();
            if (empty($ip))
                return $this->unauthorizedRes('IP Address not found in Request.');
// 
            // Log::info('UserDataAuth', [Auth::user()->toArray()]);

            // Validate the API key and token
            $user_id = $this->ipValidate($ip);
            if (!$user_id) {
                return $this->unauthorizedRes('Please whitelist Server IP Address.');
            }

            return $next($request);
        } catch (Exception $e) {
            return $this->unauthorizedRes($e->getMessage());
        }
    }


    private function ipValidate($ip)
    {
        $account = AccountSetting::where('user_id', Auth::user()->_id)->first();

        // Log::info('User Account', [Auth::user()->toArray()]);
        if (empty($account))
            return false;
        $ipAddress = $account->ip_address;
        if ($ipAddress == $ip)
            return true;

        return false;
    }
}
