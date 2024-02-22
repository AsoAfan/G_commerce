<?php


use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:sanctum')->group(function () {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthSessionController::class, 'store']);


    Route::post('/verify/resend', [EmailVerificationController::class, 'update'])->middleware('throttle:otpRequest');
    Route::post('/verify/{user:otp_slug}', EmailVerificationController::class)
        ->missing(fn() => response(['message' => "Not Found"], 404));


    // TODO: All Users later


});
