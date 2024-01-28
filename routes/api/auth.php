<?php

use App\Http\Controllers\AuthSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthSessionController::class, 'show']);

    Route::post('logout', [AuthSessionController::class, 'destroy']);
});
