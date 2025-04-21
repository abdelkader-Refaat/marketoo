<?php

use Illuminate\Support\Facades\Route;
use Modules\Posts\App\Http\Controllers\Front\PostController;

// Route::prefix('posts')
//     ->name('posts.')
//     ->controller(PostController::class)
//     ->group(function () {
//         Route::resource('/', PostController::class);

//         Route::get('events', 'events')->name('events');
//         Route::get('promoted', 'promoted')->name('promoted');
//         Route::get('archived', 'archived')->name('archived');
//         Route::get('settings', 'settings')->name('settings');
//     });
Route::middleware(['auth'])->group(function () {


    Route::controller(PostController::class)
    ->group(function () {
        Route::resource('posts', PostController::class);

        Route::prefix('posts')->name('posts.')->group(function () {

        Route::get('events', 'events')->name('events');
        Route::get('promoted', 'promoted')->name('promoted');
        Route::get('archived', 'archived')->name('archived');
        Route::get('settings', 'settings')->name('settings');
        });
    });

    // // Posts resource routes
    // Route::get('/posts', [PostController::class, 'index'])->name('site.posts.index');
    // Route::get('/posts/create', [PostController::class, 'create'])->name('site.posts.create');
    // Route::post('/posts', [PostController::class, 'store'])->name('site.posts.store');
    // Route::get('/posts/{id}', [PostController::class, 'show'])->name('site.posts.show');
    // Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('site.posts.edit');
    // Route::put('/posts/{id}', [PostController::class, 'update'])->name('site.posts.update');
    // Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('site.posts.destroy');

    // // Special post routes
    // Route::get('/posts/events', [PostController::class, 'events'])->name('site.posts.events');
    // Route::get('/posts/promoted', [PostController::class, 'promoted'])->name('site.posts.promoted');
    // Route::get('/posts/archived', [PostController::class, 'archived'])->name('site.posts.archived');
});
