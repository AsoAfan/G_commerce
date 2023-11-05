<?php


use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:sanctum'])->group(function () {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthSessionController::class, 'store']);


    Route::get('/verify/resend', [\App\Http\Controllers\EmailVerificationController::class, 'update'])->middleware('throttle:otpRequest');
    Route::post('/verify/{otp:slug}', \App\Http\Controllers\EmailVerificationController::class)
        ->missing(fn() => response(['message' => "Not Found"], 404));


});
