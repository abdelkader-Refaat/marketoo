<?php

use app\Http\Controllers\Api\V1\User\Individual\AuthController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Api\V1\User\ProfileController;

Route::group(['middleware' => ['guest:sanctum'], 'controller' => AuthController::class], function () {
    Route::post('register', 'register')->name('register');
    Route::post('log-in', 'login');
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

Route::middleware(['auth:user', 'is-active'])->group(function () {
    Route::post('delete-account', [AuthController::class, 'deleteAccount']);
//    Start of Notifications
    require __DIR__.'/../notification.php';
//    End of Notifications

//    start of Profile
    Route::group(['prefix' => 'profile', 'controller' => ProfileController::class], function () {
        Route::get('/', 'profile');
        Route::put('update', 'update');
        Route::patch('update-password', 'updatePassword');
    });
//    End Of Profile
});
