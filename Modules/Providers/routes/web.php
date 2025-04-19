<?php

use Illuminate\Support\Facades\Route;
use Modules\Providers\App\Http\Controllers\ProvidersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('providers', ProvidersController::class)->names('providers');
});
