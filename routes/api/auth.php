<?php

use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthSessionController::class, 'show']);

    Route::post('logout', [AuthSessionController::class, 'destroy']);


    Route::post('/products/{product}/rate', [ProductController::class, 'rate']);
    Route::post('/products/{product}/favourite', [ProductController::class, 'favourite']);
});
