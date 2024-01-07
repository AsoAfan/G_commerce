<?php

namespace App\Traits;

use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

trait EmailVerificationTrait
{

    function sendOtp($email)
    {


        if ($this->isVerified($email)) {
            return response([
                    'message' => $email . " is already Verified",
                    'code' => 202
                ], 202);

        }

        $user = User::where('email', $email)->first(); // Is this a good way or using userEmail to check weather
        // email is passed or user?

        $otp_code = mt_rand(100000, 999999);
        $otp_hashed = Hash::make($otp_code);
        $otp_slug = Str::slug($otp_hashed . Str::substr($user->id, 0, 1));

//        dd($user->otp->secret);

        if ($user->otp) {
            $user->otp()->update([

                'hashed' => $otp_hashed,
                'slug' => $otp_slug,
                'expires_at' => now()->addMinutes(5),
                'user_id' => $user->id

            ]);

        } else {

            $otp = Otp::create([
                'hashed' => $otp_hashed,
                'slug' => $otp_slug,
                'expires_at' => now()->addMinutes(5),
                'user_id' => $user->id
            ]);
        }

        $otp = $user->otp?->fresh() ?: $otp;

        $user->notify(new OtpNotification($otp_code));

        RateLimiter::hit('otpRequest');


        return [
            'message' => "verify Email sent",
            'data' => $otp->slug,
            'code' => 200
        ];

    }


    function isVerified($userOrEmail): Application|ResponseFactory|\Illuminate\Foundation\Application|Response|bool
    {
        $user = $userOrEmail;

        if (!$userOrEmail instanceof User)
            $user = User::where('email', $userOrEmail)->first();


        return (bool)$user?->email_verified_at;


    }

    function isOtpExpired($otp_code): bool
    {

        return now() > $otp_code;

    }

}
