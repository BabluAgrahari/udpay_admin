<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        Gate::define('isGuest', function (User $user) {
            return $user->role == 'guest';
        });

        Gate::define('isCustomer', function (User $user) {
            return $user->role == 'customer';
        });

        Gate::define('isDistributor', function (User $user) {
            return $user->role == 'distributor';
        });

        Paginator::defaultView('Pagination');
    }
}
