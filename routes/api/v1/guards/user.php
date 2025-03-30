<?php

use app\Http\Controllers\Api\V1\User\Individual\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest:sanctum'], 'controller' => AuthController::class], function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login');
    Route::post('resend-code', 'resendCode');
    Route::post('check-code', 'activate');
});

Route::group(['middleware' => ['OptionalSanctumMiddleware',]], function () {
    // Routes here
});

Route::group(['middleware' => ['auth:user', 'is_blocked']], function () {
    // Routes here
    // complete account information
    Route::group(['prefix' => 'individual-user', 'controller' => AuthController::class], function () {
        Route::post('complete-account-info', 'completeData');
    });

    Route::middleware('MustCompleteData')->group(function () {
    });
});

Route::middleware('auth:user')->group(function () {
    Route::group(['prefix' => 'notification'], function () {
        require __DIR__.'/../notification.php';
    });
});
