<?php

namespace App\Providers;

use App\Models\PublicSettings\SiteSetting;
use App\Models\PublicSettings\Social;
use App\Services\Core\SettingService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $settings;

    protected $socials;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!$this->app->isProduction()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        Model::automaticallyEagerLoadRelationships();  // laravel 12 latest using eager load all relations rather than use load and with for model relations to avoid n+1 problem
        Model::shouldBeStrict(!$this->app->isProduction()); // prevent lazy loading queries
        DB::prohibitDestructiveCommands($this->app->isProduction());  // prevent DB:fresh commands

        $folders = array_diff(scandir(database_path().'/migrations'), ['..', '.']);
        $this->loadMigrationsFrom(
            array_map(function ($folder) {
                return database_path().'/migrations/'.$folder;
            }, $folders)
        );

        try {
            if (Schema::hasTable('site_settings')) {
                $this->settings = Cache::rememberForever('settings', function () {
                    return SettingService::appInformations(SiteSetting::pluck('value', 'key'));
                });
            } else {
                $this->settings = [];
            }

            if (Schema::hasTable('socials')) {
                $this->socials = Cache::rememberForever('socials', function () {
                    return Social::get();
                });
            } else {
                $this->socials = [];
            }
        } catch (Exception $e) {
            echo 'app service provider exception :::::::::: '.$e->getMessage();
            $this->settings = [];
            $this->socials = [];
        }

        view()->composer('admin.*', function ($view) {
            $view->with([
                'settings' => $this->settings,
            ]);
        });

        // -------------- lang ---------------- \\
        app()->singleton('lang', function () {
            return session()->get('lang', 'ar');
        });
        view()->composer('*', function ($view) {
            $view->with('currentLocale', app()->getLocale());
        });
        // -------------- lang ---------------- \\
    }
}
