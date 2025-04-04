<?php

use app\Http\Controllers\Api\V1\General\CountriesCitiesController;
use app\Http\Controllers\Api\V1\General\SettingController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['OptionalSanctumMiddleware']], function () {
    Route::controller(SettingController::class)->group(function () {
        // change language
        Route::patch('change-lang', 'changeLang');
        // get terms and conditions
        Route::get('terms/{type}', 'terms');
    });

    // get countries and cities list
    Route::group(['prefix' => 'countries', 'controller' => CountriesCitiesController::class], function () {
        // get countries
        Route::get('/', 'getCountries');
        // get cities of specific country
        Route::get('{country_id}/cities', 'getCountryCities');
    });
    // New Routes here
});

Route::group(['middleware' => ['auth:sanctum', 'is_blocked']], function () {
    // Routes here
});
