<?php

use Illuminate\Support\Facades\Route;
use Modules\Providers\App\Http\Controllers\ProvidersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('providers', ProvidersController::class)->names('providers');
});
