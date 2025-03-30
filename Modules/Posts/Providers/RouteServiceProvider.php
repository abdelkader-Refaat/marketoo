<?php

namespace Modules\Posts\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Posts';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
        Route::middleware('web')->group(function () {
            Route::get('/admin/switch-lang/{lang}', function ($lang, Request $request) {
                if (in_array($lang, ['en', 'ar'])) {
                    session(['locale' => $lang]);
                    app()->setLocale($lang);
                }
                return back();
            })->name('filament.lang.switch');
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/v1')
            ->name('api.')
            ->group(module_path($this->name, 'routes/api_v1.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/web.php'));
    }
}
