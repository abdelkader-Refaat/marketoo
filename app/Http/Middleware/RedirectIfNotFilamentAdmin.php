<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotFilamentAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('filament')->check()) {
            return redirect()->route('filament.auth.login');
        }

        return $next($request);
    }
}
