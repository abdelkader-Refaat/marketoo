<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\App\Http\Controllers\ContactsController;

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
    Route::apiResource('contacts', ContactsController::class)->names('contacts');
});
