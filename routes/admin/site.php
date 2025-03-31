<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use app\Http\Controllers\Apis\V1\Payment\PaymentController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('payment', function () {
    return view('payment.checkout_form');
});

Route::get('payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');
Route::post('payment/checkout', [PaymentController::class, 'paymentProcess'])->name('payment.process');





