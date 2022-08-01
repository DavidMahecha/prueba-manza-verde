<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(App\Http\Controllers\AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
    });

Route::controller(App\Http\Controllers\FoodController::class)
    ->prefix('foods')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('', 'index');
        Route::get('/{food}', 'show');
    });

Route::controller(App\Http\Controllers\OrderController::class)
    ->prefix('orders')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('{order}', 'show');
        Route::put('confirm/{order}', 'confirm');
        Route::put('destroy/{order}', 'destroy');
    });