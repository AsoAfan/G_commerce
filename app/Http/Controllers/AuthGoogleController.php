<?php

namespace App\Http\Controllers;

use App\Traits\SocialTrait;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{
    use SocialTrait;

    public function __invoke()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {

        $googleUser = Socialite::driver('google')->user();

        $user = $this->findOrCreate($googleUser);

        // TODO: FIX LATER
        return [
            'message' => "login succeed",
            'data' => [
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ]
        ];

    }
}
