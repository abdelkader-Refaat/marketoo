<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apis\V1\Auth\AuthController;
use app\Http\Controllers\Apis\V1\Payment\PaymentController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});

Route::prefix('payment')->group(function () {
    Route::post('process', [PaymentController::class, 'paymentProcess'])->name('process');
    Route::get('callback', [PaymentController::class, 'callBack'])->name('callback');
});




