<?php

use Illuminate\Support\Facades\Route;
use Modules\Admins\Http\Controllers\AdminsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('admins', AdminsController::class)->names('admins');
});
