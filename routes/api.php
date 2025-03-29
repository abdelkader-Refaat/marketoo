<?php

use App\Http\Controllers\Apis\Auth\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\Payment\PaymentController;

Route::prefix('v1')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');
    Route::post('payment/process', [PaymentController::class, 'paymentProcess']);
    Route::get('payment/callback', [PaymentController::class, 'callBack']);
});
