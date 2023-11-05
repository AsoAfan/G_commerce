<?php

namespace App\Traits;

use App\Models\User;

trait SocialTrait
{

    public function findOrCreate($socialUser)
    {

        $user = User::where('email', $socialUser->getEmail())->first();

        return $user ?: User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
        ]);

    }


}