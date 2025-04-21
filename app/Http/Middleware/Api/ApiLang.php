<?php

namespace App\Http\Middleware\Api;

use App;
use Carbon\Carbon;
use Closure;

class ApiLang
{
    public function handle($request, Closure $next)
    {
        $lang = defaultLang();
        $requestLang = $request->header('Lang') ?? $request->lang ?? null;
        if ($requestLang != null && in_array($requestLang, languages())) {
            $lang = $requestLang;
        } elseif (auth()->check()) {
            $lang = optional(auth()->user())->lang;
        }

        App::setLocale($lang);
        Carbon::setLocale($lang);

        return $next($request);
    }
}
