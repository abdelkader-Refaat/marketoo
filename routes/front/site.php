<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('front/welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('front/dashboard');
    })->name('dashboard');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('front/Payment/Initiate');
        })->name('initiate');

        Route::get('hyperpay/form', function () {
            return Inertia::render('front/Payment/HyperPayForm');
        })->name('hyperpay.form');
    });
});
