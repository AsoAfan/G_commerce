<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user',[\App\Http\Controllers\AuthSessionController::class, 'show']);

    Route::post('logout', [\App\Http\Controllers\AuthSessionController::class, 'destroy']);
});
