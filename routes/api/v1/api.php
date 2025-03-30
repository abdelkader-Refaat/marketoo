<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\V1\Auth\AuthController;
use app\Http\Controllers\Apis\V1\Payment\PaymentController;

//Route::prefix('auth')->group(function () {
//    Route::post('register', [AuthController::class, 'register'])->name('register');
//});

Route::middleware('auth:sanctum')->group(function () {
//    Route::group(['prefix' => 'notification'], function () {
//        require __DIR__.'/notification.php';
//    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
    });

Route::prefix('payment')->group(function () {
    Route::post('process', [PaymentController::class, 'paymentProcess'])->name('process');
    Route::get('callback', [PaymentController::class, 'callBack'])->name('callback');
    });

//Route::group(['prefix' => 'general'], function () {
//    require __DIR__.'/guards/general.php';
//});
//
//Route::group(['prefix' => 'user'], function () {
//    require __DIR__.'/guards/user.php';
//});



