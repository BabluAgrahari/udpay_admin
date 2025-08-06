<?php

namespace App\Http\Middleware;

use App\Traits\Response;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DistributorAuth
{
    use Response;

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role != 'distributor') {
            return redirect()->route('home');
        }
        return $next($request);
    }
}
