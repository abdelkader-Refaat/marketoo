<?php

namespace Modules\Posts\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Posts\App\Models\Post;
use Modules\Posts\App\Observers\PostObserver;
use Modules\Posts\App\Policies\PostPolicy;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PostsServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Posts';

    protected string $nameLower = 'posts';

    public function boot(): void
    {
        // Register the policy
        Gate::policy(Post::class, PostPolicy::class);
        Post::observe(PostObserver::class);

        // Skip locale handling here as it's handled by middleware
        // This avoids conflicts with your SiteLang middleware

        Filament::serving(function () {
            // If additional Filament-specific logic is needed, add it here
        });

        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    protected function registerTranslations(): void
    {
        $langPath = app_path('Modules/Posts/lang/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    protected function registerConfig(): void
    {
        $relativeConfigPath = config('modules.paths.generator.config.path');
        $configPath = module_path($this->name, $relativeConfigPath);

        if (is_dir($configPath)) {
            try {
                $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

                foreach ($iterator as $file) {
                    if ($file->isFile() && $file->getExtension() === 'php') {
                        $relativePath = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                        $configKey = $this->nameLower.'.'.str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''],
                                $relativePath);
                        $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                        $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                        $this->mergeConfigFrom($file->getPathname(), $key);
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue execution
                \Log::error('Error in Posts module config loading: '.$e->getMessage());
            }
        }
    }

    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        try {
            $componentNamespace = $this->module_namespace($this->name,
                $this->app_path(config('modules.paths.generator.component-class.path')));
            Blade::componentNamespace($componentNamespace, $this->nameLower);
        } catch (\Exception $e) {
            // Log error but continue execution
            \Log::error('Error in Posts module component namespace: '.$e->getMessage());
        }
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }

    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    public function provides(): array
    {
        return [];
    }
}