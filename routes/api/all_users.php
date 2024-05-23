<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->missing(fn() => missingRoute());


Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/discounts/{discount}', [DiscountController::class, 'show'])
    ->missing(fn() => missingRoute());

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show'])
    ->missing(fn() => missingRoute());

Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons/{coupon:code}', [CouponController::class, 'show'])
    ->missing(fn() => missingRoute());


Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{brand}', [BrandController::class, 'show'])
    ->missing(fn() => missingRoute());

Route::get('/groups', [GroupController::class, 'index']);
Route::get('/groups/{group}', [GroupController::class, 'show'])
    ->missing(fn() => missingRoute());

Route::get('/{category}/subcategories', [SubCategoryController::class, 'index'])
    ->missing(fn() => missingRoute());
Route::get('subcategories/{subcategory}', [SubCategoryController::class, 'show'])
    ->missing(fn() => missingRoute());
