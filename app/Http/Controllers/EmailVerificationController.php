<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpResendRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Models\User;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    use EmailVerificationTrait;

    public function __invoke(OtpVerificationRequest $request, User $user)
    {
        // refactor this later because it is duplicated several times
        if ($this->isVerified($user))
            return response(['message' => $user->email . " is already Verified", 'code' => 202], 202);

        if ($this->isOtpExpired($user->otp_expires_at)) {
            return response(['message' => "Code has been expired", 'code' => 403], 403);
        }

        if (!Hash::check($request->post('secret'), $user->otp_secret))
            return response(['message' => "Invalid verification code", 'code' => 400], 400);

        $user->update([
            "otp_code" => null,
            "otp_secret" => null,
            "otp_slug" => null,
            "otp_expires_at" => null,
            'email_verified_at' => now()
        ]);

        return [
            'message' => $user->email . " Verified successfully",
            'code' => 200
        ];

    }

    // Resend verification email
    // combine
    public function update(OtpResendRequest $request)
    {
        
        return $this->send($request->post('email'));
    }
}
