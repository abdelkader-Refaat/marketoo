<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('front/welcome');
});
    
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('front/dashboard');
    })->name('dashboard');
});

// ✅ Move language switcher outside auth middleware
Route::get('/switch-lang/{locale}', function ($locale) {
    if (!in_array($locale, languages())) {
        abort(400);
    }
    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
})->name('switch.lang');

