<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SiteLang
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Get language from session or default to 'ar'
            $lang = session()->get('lang', 'ar');

            // Validate the language
            if (! in_array($lang, languages())) {
                $lang = 'ar';
            }

            // Set the application locale
            App::setLocale($lang);
            Carbon::setLocale($lang);

            // Share with all views
            view()->share('currentLocale', $lang);

        } catch (\Exception $e) {
            Log::error('Language middleware error: '.$e->getMessage());
            // Fallback to Arabic if anything goes wrong
            App::setLocale('ar');
            Carbon::setLocale('ar');
        }

        return $next($request);
    }
}
