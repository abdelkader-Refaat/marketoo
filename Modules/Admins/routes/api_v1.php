<?php

use Illuminate\Support\Facades\Route;
use Modules\Admins\App\Http\Controllers\AdminsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('admins', AdminsController::class)->names('admins');
});
