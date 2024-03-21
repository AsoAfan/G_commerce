<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->missing(fn() => missingRoute());
    Route::delete('/products/delete/{product}', [ProductController::class, 'destroyPermanently'])
        ->missing(fn() => missingRoute());


    Route::post('/discounts/create', [DiscountController::class, 'store']);
    Route::put('/discounts/{discount}', [DiscountController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy'])
        ->missing(fn() => missingRoute());


    Route::post('/brands/create', [BrandController::class, 'store']);
    Route::put('/brands/{brand}', [BrandController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])
        ->missing(fn() => missingRoute());


    Route::post('/groups/create', [GroupController::class, 'store']);
    Route::put('/groups/{group}', [GroupController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])
        ->missing(fn() => missingRoute());


    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->missing(fn() => missingRoute());


    Route::post('/coupons/create', [CouponController::class, 'store']);
    Route::put('/coupons/{coupon}', [CouponController::class, 'update'])
        ->missing(fn() => missingRoute());
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])
        ->missing(fn() => missingRoute());


    Route::post('/subcategories/create', [SubCategoryController::class, 'store']);


    Route::get('/users', [UserController::class, 'index']);


});
