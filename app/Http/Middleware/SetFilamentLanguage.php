<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('language') && in_array($request->language, ['en', 'es', 'ar'])) {
            app()->setLocale($request->language);
            session()->put('language', $request->language);
        } elseif (session()->has('language')) {
            app()->setLocale(session()->get('language'));
        }

        return $next($request);
    }
}
