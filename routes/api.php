<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'jsonify'], function () {

    require_once __DIR__ . '/api/admin.php';
    require_once __DIR__ . '/api/auth.php';
    require_once __DIR__ . '/api/guest.php';
    require_once __DIR__ . '/api/all_users.php';


});


