<?php

use App\Http\Controllers\AuthFacebookController;
use App\Http\Controllers\AuthGoogleController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest:sanctum')->group(function (){

    Route::get('/auth/facebook', AuthFacebookController::class);
    Route::get('/facebook/callback', [AuthFacebookController::class, 'callback']);

    Route::get('/auth/google', AuthGoogleController::class);
    Route::get('/google/callback', [AuthGoogleController::class, 'callback']);

});