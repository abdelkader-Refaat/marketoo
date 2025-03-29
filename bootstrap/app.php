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

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [__DIR__ . '/../routes/web.php', __DIR__ . '/../routes/site.php'],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
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
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings'         => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'           => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'HtmlMinifier'     => \App\Http\Middleware\HtmlMifier::class,
            'is-active'        => \App\Http\Middleware\CheckAuthStatus::class,
            'api_is_blocked'   => \App\Http\Middleware\Api\IsBlockedMiddleware::class,
            'is_blocked'       => \App\Http\Middleware\Admin\IsBlockedMiddleware::class,
            'is-approved'      => \App\Http\Middleware\Api\CheckAuthUserApproveStatus::class,
            'MustCompleteData' => \App\Http\Middleware\Api\MustCompleteDataMiddleware::class,
            'admin'      => \App\Http\Middleware\Admin\AdminMiddleware::class,
            // 'check-role'                        => \App\Http\Middleware\Admin\CheckRoleMiddleware::class,
            'admin-lang' => \App\Http\Middleware\Admin\AdminLang::class,
            'web-cors'   => \App\Http\Middleware\WebCors::class,
            'api-lang'                  => \App\Http\Middleware\Api\ApiLang::class,
            // 'api-cors'                          => \App\Http\Middleware\Api\ApiCors::class,
            'OptionalSanctumMiddleware' => \App\Http\Middleware\Api\OptionalSanctumMiddleware::class,
            'abilities'                 => CheckAbilities::class,
            'ability'                   => CheckForAnyAbility::class,
        ]);

        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SiteLang::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
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
                    'key'    => 'fail',
                    'code'              => ResponseAlias::HTTP_NOT_FOUND,
                    'msg'               => 'Model Not Found' ?? $exception->getMessage(),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data'   => []
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        });
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key'    => 'fail',
                    'code'              => ResponseAlias::HTTP_NOT_FOUND,
                    'msg'               => 'Not Found Http' ?? $exception->getMessage(),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data'   => []
                ],
                    ResponseAlias::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key'    => 'unauthenticated',
                    'code'              => ResponseAlias::HTTP_UNAUTHORIZED,
                    'msg'               => trans('auth.unauthenticated'),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => [],
                    ],
                    'data'   => []
                ], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        });

        $exceptions->render(function (ParseError $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key'    => 'fail',
                    'code'              => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                    'msg'               => $exception->getMessage(),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data'   => []
                ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }
        });

        $exceptions->render(function (Error $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key'    => 'fail',
                    'code'              => $exception->status,
                    'msg'               => $exception->getMessage(),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => ['line' => $exception->getLine(), 'file' => $exception->getFile()],
                    ],
                    'data'   => []
                ], $exception->status);
            }
        });

        $exceptions->render(function (Exception $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'key'    => 'fail',
                    'code'              => $exception->status,
                    'msg'               => $exception->getMessage(),
                    'status' => [
                        'error'             => true,
                        'validation_errors' => [
                            'line' => $exception->getLine(), 'file' => $exception->getFile()
                        ],
                    ],
                    'data'   => []
                ], $exception->status);
            }
        });
    })->create();
