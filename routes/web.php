<?php

use App\Models\PasswordReset;
use App\Notifications\LoginAttemptNotification;
use Illuminate\Support\Facades\Notification;
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
    return view('docs');
});

Route::get('/sanctum/csrf-cookie', function () {
    return [
        'message' => 'CSRF set',
        'data' => [
//        'csrf' => csrf_token()
        ]
    ];
});

Route::get('/reset/{token:token}', function (PasswordReset $token) {
//    dd($token);
    return view('reset_password', ['token' => $token]);
});

Route::get('/test', function () {
    $user = \App\Models\User::all()->first();
    return (new LoginAttemptNotification())->toMail(Notification::route('mail', "aso.afan@yahoo.com"));
});

require __DIR__ . '/web/guest.php';


//Route::get('throttle/test', function () {
//    return ["message" => "HI"];
//})->middleware('throttle:authActions');
