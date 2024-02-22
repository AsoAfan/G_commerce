<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAllUsersRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\PaginationService;
use App\Traits\EmailVerificationTrait;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use EmailVerificationTrait;


    /**
     * Display a listing of the resource.
     */
    public function index(PaginationService $paginator, GetAllUsersRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(User::all());
//        return UserRes
        return [
            'message' => "succeed",
            'data' => [UserResource::collection($data)],
            'hasNextPage' => $hasNext
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrationRequest $request)
    {

//        if ($validator->fails()) return response(['message' => $validator->messages()->all()], 400);
        try {
            $newUser = User::create($request->only(['username', 'email', 'password']));
            return $this->sendOtp($newUser->email);

        } catch (UniqueConstraintViolationException $e) {

            return response([
                'message' => 'Email already assigned to another account',
                'code' => 400
            ], 400);
        }
//        return ['message' => "verify Email sent", 'data' => ['parameter' => $verify_param]];

    }

    /**
     * Display the specified resource.
     * public function show(User $user)
     * {
     * return $user;
     * }
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
