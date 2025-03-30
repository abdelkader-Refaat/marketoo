<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use app\Http\Controllers\Apis\V1\Payment\PaymentController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');
Route::get('payment', function ()
{
    return view('payment.checkout_form');
});

Route::get('payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');
Route::post('payment/checkout', [PaymentController::class, 'paymentProcess'])->name('payment.process');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// ✅ Move language switcher outside auth middleware
Route::get('/admin/switch-lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ar'])) {
        abort(400);
    }
    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
})->name('switch.lang');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
