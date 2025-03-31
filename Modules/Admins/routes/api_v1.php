<?php

use Illuminate\Support\Facades\Route;
use Modules\Admins\App\Http\Controllers\AdminsController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('admins', AdminsController::class)->names('admins');
});
