<?php

use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthSessionController::class, 'show']);

    Route::post('/logout', [AuthSessionController::class, 'destroy']);


    Route::post('/products/{product}/rate', [ProductController::class, 'rate'])
        ->missing(fn() => missingRoute());
    Route::post('/products/{product}/favourite', [ProductController::class, 'favourite'])
        ->missing(fn() => missingRoute());

    Route::get('/locations', [LocationController::class, 'index']);
    Route::post('/locations/create', [LocationController::class, 'store']);

//    Route::get('/locations/{location}', [LocationController::class, 'show'])
//        ->missing(fn() => missingRoute());

    Route::put('/locations/{location}', [LocationController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('locations/{location}', [LocationController::class, 'destroy'])
        ->missing(fn() => missingRoute());

    Route::post('/carts/add', [CartController::class, 'storeItems']);
    Route::get('/cart/', [CartController::class, 'show']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::delete('/cart/remove/{product}', [CartController::class, 'removeItem']);

    Route::post('/orders/create', [\App\Http\Controllers\OrderController::class, 'store']);

});
