<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomerOrDistributorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->can('isCustomer') || Auth::user()->can('isDistributor')) {
            return $next($request);
        }

        return redirect()->route('/')->withErrors('Unauthorized access');
    }
}