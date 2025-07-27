<?php

namespace App\Http\Middleware;

use App\Traits\Response;
use Closure;
use Exception;
use App\Models\AccountSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ClientMiddleware
{
    use Response;

    public function handle(Request $request, Closure $next)
    {

        try {
            $client_id = $request->header('clientId');
            if (empty($client_id))
                return $this->unauthorizedRes('Client Id not found in Header.');

            // Validate the API key and token
            $user_id = $this->clientId($client_id);
            if (!$user_id) {
                return $this->unauthorizedRes('Invaliad Client ID.');
            }

            return $next($request);
        } catch (Exception $e) {
            return $this->unauthorizedRes($e->getMessage());
        }
    }


    private function clientId($client_id)
    {
        $customer = Customer::with('Account')->Where('client_id', $client_id)->first();
        if (empty($customer->Account))
            return false;

        Auth::login($customer);

        return true;
    }
}
