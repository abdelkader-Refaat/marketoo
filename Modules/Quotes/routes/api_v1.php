<?php

use Illuminate\Support\Facades\Route;
use Modules\Quotes\App\Http\Controllers\QuotesController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('quotes', QuotesController::class)->names('quotes');
});
