<?php

use app\Http\Controllers\Api\V1\User\Individual\AuthController;
use app\Http\Controllers\Api\V1\User\ProfileController;
use App\Http\Controllers\Apis\V1\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest:sanctum'], 'controller' => AuthController::class], function () {
    Route::post('register', 'register')->name('register');
    Route::post('log-in', 'login');
    Route::post('resend-code', 'resendCode');
    Route::post('check-code', 'activate');
});

Route::middleware(['OptionalSanctumMiddleware'], function () {
    // Routes here
});

Route::group(['middleware' => ['auth:user', 'is_blocked']], function () {
    // Routes here

    // complete account information
    Route::controller(AuthController::class, function () {
        Route::post('complete-account-info', 'completeData');
    });
    Route::middleware(['MustCompleteData'], function () {
        //
    });

    Route::middleware(['auth:user', 'is-active'])
        ->group(function () {
            //    handle notifications route
            require_once __DIR__.'/../notification.php';
            Route::post('delete-account', [AuthController::class, 'deleteAccount']);

            //    start of Profile
            Route::group(['prefix' => 'profile', 'controller' => ProfileController::class], function () {
                Route::get('/', 'profile');
                Route::put('update', 'update');
                Route::patch('update-password', 'updatePassword');
            });
            //    End Of Profile

            //    Start Of Payment
            Route::prefix('payment')->name('payment.')->group(function () {
                Route::post('process', [PaymentController::class, 'paymentProcess'])->name('process');
                Route::get('callback', [PaymentController::class, 'callBack'])->name('callback');
                Route::get('success', [PaymentController::class, 'success'])->name('success');
                Route::get('failed', [PaymentController::class, 'failed'])->name('failed');

                // Status check (optional)
                Route::get('status/{transaction}', [PaymentController::class, 'status'])->name('status');
            });
            //    End Of Payment

        });
});
