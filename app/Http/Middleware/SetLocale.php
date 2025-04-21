<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->segment(1) ?? session('locale', lang());

        if (in_array($locale, languages())) {
            app()->setLocale($locale);
            session()->put('locale', $locale);
            // Set HTML direction
            $direction = $locale === 'ar' ? 'rtl' : 'ltr';
            view()->share('htmlDirection', $direction);
        }

        return $next($request);
    }
}
