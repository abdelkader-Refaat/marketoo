<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Payment/Initiate');
        })->name('initiate');

        Route::get('hyperpay/form', function () {
            return Inertia::render('Payment/HyperPayForm');
        })->name('hyperpay.form');
    });
});
