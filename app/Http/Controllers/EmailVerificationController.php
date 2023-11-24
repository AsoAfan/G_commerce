<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\OtpResendRequest;
use App\Models\Otp;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    use EmailVerificationTrait;

    public function __invoke(EmailVerificationRequest $request, Otp $otp)
    {

        $user = $otp->user;
        if ($this->isVerified($user)) return response(['message' => $user->email . " is already Verified"]); // refactor
        // later because it is duplicated several times


        if ($this->isOtpExpired($otp->expires_at)) {
            return response(['message' => "Code has been expired"], 400);
        }

        if (!Hash::check($request->input('secret'), $otp->hashed)) return response(['message' => "Invalid verification code"], 400);


        $otp->user->email_verified_at = now();
        $otp->user->save();


        return ['message' => $otp->user->email . " Verified successfully", 'data' => ['time' =>
            $otp->user->email_verified_at]];

    }

    public function update(OtpResendRequest $request)
    {
        return $this->sendOtp($request->input('email'));

//        return ['message' => 'Email sent successfully', 'data' => ['parameter' => $verify_param]];
    }
}
