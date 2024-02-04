<?php


use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:sanctum'])->group(function () {

    Route::post('/register', [UserController::class, 'store']);
    Route::post('/login', [AuthSessionController::class, 'store']);


    Route::post('/verify/resend', [EmailVerificationController::class, 'update'])->middleware('throttle:otpRequest');
    Route::post('/verify/{user:otp_slug}', EmailVerificationController::class)
        ->missing(fn() => response(['message' => "Not Found"], 404));


    // TODO: All Users later
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->missing(fn() => response(['message' => "product not found", 'code' => 404], 404));

    Route::get('/discounts', [DiscountController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/coupons', [CouponController::class, 'index']);

    Route::get('/{category:slug}/subcategories', [SubCategoryController::class, 'index']);

    // TODO: Admin later
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::post('/discounts/create', [DiscountController::class, 'store']);
    Route::post('/brands/create', [BrandController::class, 'store']);
    Route::post('/groups/create', [GroupController::class, 'store']);
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::post('/coupons/create', [CouponController::class, 'store']);
    Route::post('/subcategories/create', [SubCategoryController::class, 'store']);


});
