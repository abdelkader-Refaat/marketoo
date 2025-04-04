<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Frontend Routes
            Route::middleware('web')->group(function () {
                Route::prefix('site')
                    ->name('site.')
                    ->group(function () {
                        require base_path('routes/front/auth.php');
                        require base_path('routes/front/settings.php');
                        require base_path('routes/front/site.php');
                    });
                // Admin Routes
                Route::name('admin.')
                    ->group(base_path('routes/web.php'));
            });
            // API Routes v1
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(function () {
                    require __DIR__.'/../routes/api/v1/api.php';
                    require __DIR__.'/../routes/api/v1/guards/general.php';
                    require __DIR__.'/../routes/api/v1/guards/user.php';
                });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global Middleware
        $middleware->append([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        // Named Middleware
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'HtmlMinifier' => \App\Http\Middleware\HtmlMifier::class,
            'is-active' => \App\Http\Middleware\CheckAuthStatus::class,
            'api_is_blocked' => \App\Http\Middleware\Api\IsBlockedMiddleware::class,
            'is_blocked' => \App\Http\Middleware\Admin\IsBlockedMiddleware::class,
            'is-approved' => \App\Http\Middleware\Api\CheckAuthUserApproveStatus::class,
            'MustCompleteData' => \App\Http\Middleware\Api\MustCompleteDataMiddleware::class,
            'admin' => \App\Http\Middleware\Admin\AdminMiddleware::class,
            'admin-lang' => \App\Http\Middleware\Admin\AdminLang::class,
            'web-cors' => \App\Http\Middleware\WebCors::class,
            'api-lang' => \App\Http\Middleware\Api\ApiLang::class,
            'OptionalSanctumMiddleware' => \App\Http\Middleware\Api\OptionalSanctumMiddleware::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'inertia' => \App\Http\Middleware\HandleInertiaRequests::class,
            'filament' => \App\Http\Middleware\RedirectIfNotFilamentAdmin::class,

        ]);
        // Middleware Groups
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SiteLang::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->group('auth', [
            \App\Http\Middleware\Authenticate::class,
//            \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->group('api', [
            'throttle:60,1',
            \App\Http\Middleware\Api\ApiLang::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Api\ApiCors::class
        ]);

        $middleware->priority([
            \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_NOT_FOUND,
                    'msg' => 'Model Not Found',
                    'status' => [
                        'error' => true,
                        'validation_errors' => [
                            'line' => $e->getLine(),
                            'file' => $e->getFile()
                        ],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_NOT_FOUND,
                    'msg' => 'Not Found Http',
                    'status' => [
                        'error' => true,
                        'validation_errors' => [
                            'line' => $e->getLine(),
                            'file' => $e->getFile()
                        ],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'unauthenticated',
                    'code' => ResponseAlias::HTTP_UNAUTHORIZED,
                    'msg' => trans('auth.unauthenticated'),
                    'status' => [
                        'error' => true,
                        'validation_errors' => [],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        });

        $exceptions->render(function (ParseError $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                    'msg' => $e->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => [
                            'line' => $e->getLine(),
                            'file' => $e->getFile()
                        ],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $exceptions->render(function (Error|Exception $e, Request $request) {
            if ($request->is('api/*')) {
                $code = property_exists($e, 'status') ? $e->status : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
                return response()->json([
                    'key' => 'fail',
                    'code' => $code,
                    'msg' => $e->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => [
                            'line' => $e->getLine(),
                            'file' => $e->getFile()
                        ],
                    ],
                    'data' => []
                ], $code);
            }
        });
    })
    ->create();
