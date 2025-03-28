<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Filament\Facades\Filament;


Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// ✅ Move language switcher outside auth middleware
Route::get('/admin/switch-lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        abort(400);
    }
    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
})->name('switch.lang');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
