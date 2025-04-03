<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PublicSettings\SettingController;


Route::get('/', function () {
    return Inertia::render('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/switch-lang/{lang}',
    [SettingController::class, 'switchLang'])->name('switch_lang');






