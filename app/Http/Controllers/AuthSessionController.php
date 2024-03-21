<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Auth;

class AuthSessionController extends Controller
{

    use EmailVerificationTrait;


    public function show()
    {
        return [new UserResource(Auth::user())];

    }


    function store(LoginRequest $request)
    {
//        $request->makeSureNotRateLimited();

        $user = User::where('email', $request->post('email'))->first();

        if (!$user) {
            return response([
                "message" => "it seems you don't have account! no problem just signup",
                "code" => 404
            ], 404);
        }


        return $this->isVerified($user)
            ? $request->authenticate()
            : response([
                'message' => "Verify email first",
                'code' => 403,
                'data' => ['email' => $request->email]
            ], 403);
    }

    public function destroy()
    {
        /* (Auth::user()->tokens()->each(function ($token) {
             $token->delete();
         }));*/
//        $user = Auth::user();
        Auth::user()->currentAccessToken()->delete();
        // TODO: redirect to login page
        return ['message' => "Logout succeed", 'code' => 200];
    }
}
