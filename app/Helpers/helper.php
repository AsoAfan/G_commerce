<?php

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;


if (!function_exists('missingRoute')) {
    /**
     * @throws NotFoundException
     */
    function missingRoute()
    {
        throw new NotFoundException();
    }
}

if (!function_exists('firstWord')) {
    function firstWord(string $str): string
    {
        return ucfirst(strtok($str, ' '));
    }
}


if (!function_exists('sendOtpMail')) {
    function sendOtpMail(User $user, int $otp_code)
    {
        $user->notify(new OtpNotification($otp_code));
    }
}


if (!function_exists('resource')) {
    function resource(JsonResource $resource): JsonResponse
    {
        return response()->json($resource);

    }
}

