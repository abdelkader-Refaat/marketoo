<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\App\Http\Controllers\Front\PostController;

Route::middleware(['auth'])
    ->prefix('posts')
    ->name('posts.')
    ->controller(PostController::class)
    ->group(function () {
        // Index and store routes
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');

        // Create route
        Route::get('create', 'create')->name('create');

        // Special listing routes
        Route::get('events', 'events')->name('events');
        Route::get('promoted', 'promoted')->name('promoted');
        Route::get('archived', 'archived')->name('archived');
        Route::get('settings', 'settings')->name('settings');

        // Resource routes for show, edit, update, destroy
        Route::get('{post}', 'show')->name('show');
        Route::get('{post}/edit', 'edit')->name('edit');
        Route::match(['put', 'patch'], '{post}', 'update')->name('update');
        Route::delete('{post}', 'destroy')->name('destroy');
    });