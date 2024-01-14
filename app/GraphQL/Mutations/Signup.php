<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Traits\EmailVerificationTrait;
use Illuminate\Database\UniqueConstraintViolationException;

final readonly class Signup
{
    use EmailVerificationTrait;

    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        try {
            $newUser = User::create([
                'email' => $args['email'],
                'password' => $args['password']
            ]);
            $verify_param = '';
            if ($newUser) {
                return $this->sendOtp($newUser->email);
            }

        } catch (UniqueConstraintViolationException $e) {

            return response([
                'message' => 'Email already assigned to another account',
                'code' => 400
            ], 400);
        }


    }
}
