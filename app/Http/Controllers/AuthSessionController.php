<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Auth;

class AuthSessionController extends Controller
{

    use EmailVerificationTrait;


    function store(LoginRequest $request)
    {

        return $this->
        isVerified($request->input('email'))
            ? $request->authenticate()
            : [
                'message' => "Verify email first",
                'data' => ['email' => $request->email]
            ];
    }

    function destroy()
    {
        (Auth::user()->tokens()->each(function ($token) {
            $token->delete();
        }));

        return ['message' => "Logout succeed"];
    }
}
