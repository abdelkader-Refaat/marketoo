<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerFilamentHooks();
    }

    protected function registerFilamentHooks(): void
    {
        Filament::serving(function () {
            $this->handleLocale();
        });

        Filament::registerRenderHook('head.start', function () {
            return $this->generateLangScript();
        });
    }

    protected function handleLocale(): void
    {
        $locale = session('locale', 'en');
        App::setLocale($locale);
        Log::info('Filament serving with locale: '.$locale);
    }

    protected function generateLangScript(): string
    {
        $locale = App::getLocale();
        Log::info('Filament hook executed: setting lang = '.$locale);
        return "<script>document.documentElement.setAttribute('lang', '$locale');</script>";
    }

    public function panel(Panel $panel): Panel
    {
        return $panel->navigationItems($this->getNavigationItems());
    }

    protected function getNavigationItems(): array
    {
        return [
            $this->createNavigationItem('English', '/switch-lang/en'),
            $this->createNavigationItem('العربية', '/switch-lang/ar'),
        ];
    }

    protected function createNavigationItem(string $label, string $url): NavigationItem
    {
        return NavigationItem::make($label)
            ->url(url($url))
            ->icon('heroicon-o-globe')
            ->activeIcon('heroicon-o-globe-alt');
    }
}
