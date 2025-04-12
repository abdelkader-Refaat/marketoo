<?php

namespace Modules\Posts\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Posts';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapSiteRoutes();
    }

    protected function mapApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/v1')
            ->name('api.')
            ->group(module_path($this->name, 'routes/api_v1.php'));
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, 'routes/web.php'));
    }

    protected function mapSiteRoutes(): void
    {
        Route::middleware('web')->group(function () {
            Route::prefix('site')
                ->name('site.')
                ->group(module_path($this->name, 'routes/front/site.php')); // Updated to load from the module
        });
    }
}
