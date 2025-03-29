<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsBlockedMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard()->check() && auth()->user()->is_blocked) {
            auth()->user()->logout();
            return response()->json([
                'success' => true,
                'key' => 'blocked',
                'msg' => __('auth.blocked'),
                'message' => __('auth.blocked')
            ]);
        }
        return $next($request);
    }
}
