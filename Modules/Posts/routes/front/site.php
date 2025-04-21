<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\App\Http\Controllers\Front\PostController;

Route::prefix('posts')
    ->name('posts.')
    ->controller(PostController::class)
    ->group(function () {
        Route::resource('/', PostController::class);

        Route::get('events', 'events')->name('events');
        Route::get('promoted', 'promoted')->name('promoted');
        Route::get('archived', 'archived')->name('archived');
        Route::get('settings', 'settings')->name('settings');
    });
