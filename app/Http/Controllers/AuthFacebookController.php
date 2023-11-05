<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class AuthFacebookController extends Controller
{
    public function __invoke()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        $facebook_user = Socialite::driver('facebook')->user();
        return $facebook_user;
    }
}
