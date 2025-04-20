<?php

use app\Http\Controllers\Api\V1\General\SettingController;
use App\Http\Controllers\Site\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('intro-sliders', [SettingController::class, 'introSlider'])->name('intro-sliders');
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Payment/Initiate');
        })->name('initiate');

        Route::get('hyperpay/form', function () {
            return Inertia::render('Payment/HyperPayForm');
        })->name('hyperpay.form');
    });
});
