<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Request $request): void
    {
        //
         // Define your cookie ID (this can be dynamically generated or retrieved)
        $cookieId = $request->cookie('cart_cookie_id') ?? Str::random(40);
        // Set the cookie if it doesn't exist
        if (!$request->hasCookie('cart_cookie_id')) {
            Cookie::queue('cart_cookie_id', $cookieId, 60 * 24 * 30); // 30 days
        }
    }
}
