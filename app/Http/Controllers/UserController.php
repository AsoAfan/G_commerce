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
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(User::query());
//        return UserRes
        return [
            'message' => "succeed",
            'data' => UserResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrationRequest $request)
    {

        try {
            $newUser = User::create($request->only(['username', 'email', 'password']));
            return $this->send($newUser->email);

        } catch (UniqueConstraintViolationException) {

            return response([
                'message' => 'Email already assigned to another account',
                'code' => 400
            ], 400);
        }

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
