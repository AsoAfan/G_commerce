<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpResendRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function send(OtpResendRequest $request)
    {

        $otp = mt_rand(1000, 9999);
        $otp_hashed = Hash::make($otp);
        $otp_slug = Str::slug($otp_hashed . Str::random(4));


        PasswordReset::updateOrCreate(
            ['email' => $request->post('email')],
            ['token' => $otp_hashed, 'slug' => $otp_slug]
        );

        $user = User::firstWhere('email', $request->post('email'));

        sendOtpMail($user, $otp);

        return [
            'message' => "Email sent",
            'data' => ['slug' => $otp_slug],
            'code' => 200
        ];

    }

    public function verify(OtpVerificationRequest $request, PasswordReset $token)
    {
//        dd($token->token);
        // TODO: Maybe refactor needed
        if (!Hash::check($request->input('secret'), $token->token))
            return response([
                'message' => "Invalid verification code",
                'code' => 400
            ], 400);

        $reset = Str::random(32);
        $token->update(['reset_token' => $reset]);

        return ['data' => ['reset_token' => $reset]];


    }

    public function reset(ResetPasswordRequest $request, PasswordReset $reset)
    {


        $user = User::Where('email', $reset->email)->firstOr(fn() => missingRoute());


        $user->update($request->only('password'));

        $reset->delete();


        return ['message' => "Password reset succeed", 'code' => 200];
    }

    /* public function resend(ForgetPasswordRequest $request)
     {

         // TODO: Can this be better => Done ($this->send() used directly instead)
         return $this->send($request);
         // TODO: Update docs


     }*/
}
