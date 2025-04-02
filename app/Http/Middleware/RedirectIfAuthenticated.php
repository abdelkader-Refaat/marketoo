<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->is('admin*')) {
            return $next($request);
        }
        if (Auth::guard($guard)->check()) {
            return $guard === 'filament'
                ? redirect('/admin')  // Filament's default dashboard
                : redirect('/');      // Your frontend redirect
        }

        return $next($request);
    }
}
