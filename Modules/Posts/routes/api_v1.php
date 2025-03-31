<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\App\Http\Controllers\Api\V1\PostController;

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
    Route::apiResource('posts', PostController::class)->names('posts');
});
