<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Widget
{
    protected static string $view = 'filament.widgets.language-switcher';

    public static function canView(): bool
    {
        return false; // This prevents it from appearing in the dashboard body
    }

    public function switchLanguage($locale)
    {
        Session::put('locale', $locale);
        App::setLocale($locale);
        return redirect(request()->header('Referer')); // Refresh the page
    }
}
