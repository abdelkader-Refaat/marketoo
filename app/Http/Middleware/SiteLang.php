<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SiteLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Default language
        $lang = 'ar';

        try {
            // Get language from app singleton if available
            $sessionLang = app('lang');

            // Only use valid languages
            if ($sessionLang && in_array($sessionLang, languages())) {
                $lang = $sessionLang;
            }
        } catch (\Exception $e) {
            // If there's any error, fall back to default language
            // Log the error if needed
            // \Log::error('Language middleware error: ' . $e->getMessage());
        }

        // Set the application locale
        App::setLocale($lang);

        // Set Carbon locale for date formatting
        Carbon::setLocale($lang);

        return $next($request);
    }

}