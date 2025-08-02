<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\User;

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

        Gate::define('isAdmin', function (User $user) {
            return $user->role == 'admin';
        });

        Gate::define('isSuperAdmin', function (User $user) {
            return $user->role == 'supperadmin';
        });

        Gate::define('isCustomer', function (User $user) {
            return $user->role == 'customer' || $user->role == '';
        });

        Paginator::defaultView('Pagination');
    }
}
