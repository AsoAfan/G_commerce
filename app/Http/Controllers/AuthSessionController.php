<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Auth;

class AuthSessionController extends Controller
{

    use EmailVerificationTrait;


    public function show()
    {
        return Auth::user();

    }


    function store(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response([
                "message" => "it seems you don't have account! no problem just signup",
                "code" => 404
            ], 404);
        }

        return $this->
        isVerified($user)
            ? $request->authenticate()
            : response([
                'message' => "Verify email first",
                'code' => 403,
                'data' => ['email' => $request->email]
            ], 403);
    }

    function destroy()
    {
        (Auth::user()->tokens()->each(function ($token) {
            $token->delete();
        }));
        // TODO: Logout other devices

//        Auth::user()->tokens()->latest()->first()->delete();

        return ['message' => "Logout succeed"];
    }
}
