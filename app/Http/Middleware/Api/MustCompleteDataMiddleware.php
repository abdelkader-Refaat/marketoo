<?php

namespace App\Http\Middleware\Api;

use App\Enums\UserTypesEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustCompleteDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('user')->check() ? Auth::guard('user')->user() : null;
        if ($user && $user->type == UserTypesEnum::INDIVIDUAL->value &&
            !isset($user->name, $user->email, $user->city_id, $user->country_id)) {

            return response()->json([
                'key'    => 'go_to_complete_data',
                'code'              => Response::HTTP_UNAUTHORIZED,
                'msg'               => trans('auth.go_to_complete_data'),
                'status' => [
                    'error'             => true,
                    'validation_errors' => [],
                ],
                'data'   => []
            ], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
