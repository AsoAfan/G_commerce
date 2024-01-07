<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function () {
    return [
        'message' => 'CSRF set',
        'data' => [
//        'csrf' => csrf_token()
        ]
    ];
});

Route::get('/docs', function (){
    return view('docs');
});

require __DIR__ . '/web/guest.php';


//Route::get('throttle/test', function () {
//    return ["message" => "HI"];
//})->middleware('throttle:authActions');
