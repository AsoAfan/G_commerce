<?php


use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:sanctum')->group(function () {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/verify/resend', [EmailVerificationController::class, 'update']);
    Route::post('/verify/{user:otp_slug}', EmailVerificationController::class)
        ->missing(fn() => missingRoute());


    Route::post('/login', [AuthSessionController::class, 'store']);

    // TODO: Email verified. later ?
    // combine
    Route::post('/forget-password', [PasswordResetController::class, 'send']);
    Route::post('/forget-password/verify/{token:slug}', [PasswordResetController::class, 'verify'])
        ->missing(fn() => missingRoute());

    Route::post('/reset-password/{reset:reset_token}', [PasswordResetController::class, 'reset'])
        ->missing(fn() => missingRoute());

    Route::post('/forget-password/resend', [PasswordResetController::class, 'send']);


});
