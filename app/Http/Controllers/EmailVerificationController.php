<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\OtpResendRequest;
use App\Models\Otp;
use App\Models\User;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    use EmailVerificationTrait;

    public function __invoke(EmailVerificationRequest $request, User $user)
    {

//        dd(Hash::check($request->input('secret'), $otp->hashed));


        if ($this->isVerified($user)) return response(['message' => $user->email . " is already Verified", 'code' => 202], 202); // refactor
        // later because it is duplicated several times


        if ($this->isOtpExpired($user->otp_expires_at)) {
            return response(['message' => "Code has been expired", 'code' => 400], 400);
        }

        if (!Hash::check($request->input('secret'), $user->otp_secret)) return response(['message' => "Invalid verification code", 'code' => 422], 422);

        $user->update([
            "otp_code" => null,
            "otp_secret"=> null,
            "otp_slug" => null,
            "otp_expires_at" => null
        ]);

        $user->email_verified_at = now();
        $user->save();


        return ['message' => $user->email . " Verified successfully", 'code'=> 200,

            'data' => ['time' => $user->email_verified_at]];

    }

    // Resend verification email
    public function update(OtpResendRequest $request)
    {
        return $this->sendOtp($request->input('email'));

//        return ['message' => 'Email sent successfully', 'data' => ['parameter' => $verify_param]];
    }
}
