<?php

namespace Modules\Posts\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
            ->group(module_path($this->name, '/Routes/api_v1.php'));
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(module_path($this->name, '/Routes/web.php'));
    }

    protected function mapSiteRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('site')
            ->name('site.')
            ->group(module_path($this->name, '/Routes/front/site.php'));
    }
}
