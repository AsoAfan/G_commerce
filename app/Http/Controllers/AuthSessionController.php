<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\EmailVerificationTrait;
use Illuminate\Support\Facades\Auth;

class AuthSessionController extends Controller
{

    use EmailVerificationTrait;

    /**
     * @OA\\Get(
     *     path="/api/users",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns a list of users",
     *     @OA\\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\\JsonContent(
     *             type="array",
     *             @OA\\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     * )
     */
    public function show()
    {
        return Auth::user();

    }


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
