<?php


use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:sanctum'])->group(function () {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthSessionController::class, 'store']);


    Route::post('/verify/resend', [EmailVerificationController::class, 'update'])->middleware('throttle:otpRequest');
    Route::post('/verify/{user:otp_slug}',  EmailVerificationController::class)
        ->missing(fn() => response(['message' => "Not Found"], 404));



    Route::get('/products', [ProductController::class, 'index']);
    /*Route::get('/products/{product}', [ProductController::class, 'show'])
        ->missing(fn() => response(['message' => "product not found", 'code'=> 404], 404));*/

    // TODO: Admin later
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::post('/discounts/create', [DiscountController::class, 'store']);
    Route::post('/brands/create', [BrandController::class, 'store']);


});
