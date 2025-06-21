<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Fallback route for handling not found routes
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found',
    ], 404);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});

