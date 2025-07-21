<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        if (!$user || !$user->hasPermissionTo($permission)) {
            abort(403, 'You do not have permission.');
        }

        return $next($request);
    }
}