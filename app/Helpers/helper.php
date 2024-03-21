<?php

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\RateLimiter;

/**
 * @throws NotFoundException
 */
function missingRoute()
{
    throw new NotFoundException();
}


function firstWord(string $str): string
{
    return ucfirst(strtok($str, ' '));
}

function sendOtpMail(User $user, int $otp_code)
{
    $user->notify(new OtpNotification($otp_code));

    RateLimiter::hit('otpRequest');
}

// return response(['message' => "Not found", 'code' => 404], 404);
