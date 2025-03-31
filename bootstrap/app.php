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
        web: [
//            __DIR__.'/../routes/auth.php',    // Auth routes
//            __DIR__.'/../routes/settings.php', // Settings routes
//            __DIR__.'/../routes/web.php',      // Main web routes
//            __DIR__.'/../routes/site.php'     // Site routes
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin Web Routes Group
            // Admin Routes - Each with its own prefix and name
            Route::middleware('web')->group(function () {
                // Auth routes - /admin/auth
                Route::prefix('admin/auth')
                    ->name('admin.auth.')
                    ->group(base_path('routes/admin/auth.php'));

                // Settings routes - /admin/settings
                Route::prefix('admin/settings')
                    ->name('admin.settings.')
                    ->group(base_path('routes/admin/settings.php'));

                // Main web routes - /admin
                Route::name('admin.')
                    ->group(base_path('routes/web.php'));

                // Site routes - /admin/site
                Route::prefix('admin/site')
                    ->name('admin.site.')
                    ->group(base_path('routes/admin/site.php'));
            });
            // API Version 1 routes
            Route::middleware('api')
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(function () {
                    // General routes (no additional prefix)
                    require __DIR__.'/../routes/api/v1/api.php';

                    Route::prefix('general')
                        ->group(function () {
                            require __DIR__.'/../routes/api/v1/guards/general.php';
                        });

                    Route::prefix('individual-user')
                        ->group(function () {
                            require __DIR__.'/../routes/api/v1/guards/user.php';
                        });

//                    Route::prefix('provider')
//                        ->group(function () {
//                            require __DIR__.'/../routes/api/v1/guards/provider.php';
//                        });
                    // Add other route files as needed
                });

            // Future-proof structure for new versions
            // Route::middleware('api')
            //     ->prefix('api/v2')
            //     ->name('api.v2.')
            //     ->group(function () {
            //         require __DIR__.'/../routes/api/v2/auth.php';
            //     });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\CheckForMaintenanceMode::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

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


        ]);

        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SiteLang::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\HandleInertiaRequests::class, // Inertia middleware
        ]);
        // Auth middleware group
        $middleware->group('auth', [
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\EnsureEmailIsVerified::class,
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
        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_NOT_FOUND,
                    'msg' => 'Model Not Found' ?? $exception->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_NOT_FOUND,
                    'msg' => 'Not Found Http' ?? $exception->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
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

        $exceptions->render(function (ParseError $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                    'msg' => $exception->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data' => []
                ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $exceptions->render(function (Error $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => $exception->status,
                    'msg' => $exception->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data' => []
                ], $exception->status);
            }
        });

        $exceptions->render(function (Exception $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key' => 'fail',
                    'code' => $exception->status,
                    'msg' => $exception->getMessage(),
                    'status' => [
                        'error' => true,
                        'validation_errors' => [
                            'line' => $exception->getLine(), 'file' => $exception->getFile()
                        ],
                    ],
                    'data' => []
                ], $exception->status);
            }
        });
    })->create();
