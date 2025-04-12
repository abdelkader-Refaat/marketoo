<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\App\Http\Controllers\Front\PostController;

Route::middleware(['auth.basic'])->group(function () {
    # Start of posts routes
    Route::resource('posts', PostController::class);
    Route::controller(PostController::class)->group(function () {
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/events', 'events')->name('events');
            Route::get('/promoted', 'promoted')->name('promoted');
            Route::get('/archived', 'archived')->name('archived');
            Route::get('/settings', 'settings')->name('settings');
        });
    });
    # End of Posts Routes
});
