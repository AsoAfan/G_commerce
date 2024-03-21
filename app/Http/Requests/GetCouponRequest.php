<?php

namespace App\Http\Requests;

use App\Exceptions\TooManyRequestsException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class GetCouponRequest extends FormRequest
{
    private int $maxAttempts = 5;

    protected function prepareForValidation()
    {
        $this->makeSureNotRateLimited();
    }

    public function makeSureNotRateLimited()
    {
//        RateLimiter::hit($this->throttleKey(), 900);

        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts))
            return;

        throw new TooManyRequestsException();
    }

    private function throttleKey(): string
    {
        return Str::lower(
            "coupon|" . optional(auth('sanctum')->user())->email . "|" . $this->ip()
        );
    }
}
