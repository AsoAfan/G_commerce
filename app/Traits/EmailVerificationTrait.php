<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait EmailVerificationTrait
{
    function send($email)
    {
        if ($this->isVerified($email)) {
            return response([
                'message' => $email . " is already Verified",
                'code' => 202
            ], 202);
        }

        $user = User::where('email', $email)->firstOr(fn() => missingRoute());

        $otp_code = mt_rand(100000, 999999);
        $otp_hashed = Hash::make($otp_code);
        $otp_slug = Str::slug($otp_hashed . Str::substr($user->id, 10, 4));

        $user->update([
            'otp_code' => $otp_code,
            'otp_slug' => $otp_slug,
            'otp_secret' => $otp_hashed,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        sendOtpMail($user, $otp_code);

        return [
            'message' => "verify Email sent",
            'data' => $user->otp_slug,
            'code' => 200
        ];
    }


    function isVerified($userOrEmail)
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
