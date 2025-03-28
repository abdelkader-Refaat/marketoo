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
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->navigationItems([
                NavigationItem::make('English')
                    ->url(url('/switch-lang/en'))
                    ->icon('heroicon-o-globe')
                    ->activeIcon('heroicon-o-globe-alt'),

                NavigationItem::make('العربية')
                    ->url(url('/switch-lang/ar'))
                    ->icon('heroicon-o-globe')
                    ->activeIcon('heroicon-o-globe-alt'),
            ]);
    }
    public function boot(): void
    {
        Filament::serving(function () {
            Log::info('Filament serving...');
            App::setLocale(session('locale', 'en'));
        });

        Filament::registerRenderHook('head.start', function () {
            Log::info('Filament hook executed: setting lang = ' . App::getLocale());
            return "<script>document.documentElement.setAttribute('lang', '" . App::getLocale() . "');</script>";
        });
    }
}
