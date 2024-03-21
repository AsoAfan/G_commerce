<?php

namespace App\Http\Requests;

use App\Exceptions\TooManyRequestsException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class OtpResendRequest extends FormRequest
{
    private int $maxAttempts = 5;

    protected function prepareForValidation(): void
    {
        $this->makeSureNotRateLimited();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required | email'
        ];
    }

    /**
     * @throws TooManyRequestsException
     */
    public function makeSureNotRateLimited(): void
    {
        RateLimiter::hit($this->throttleKey());

        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts))
            return;

        throw new TooManyRequestsException();
    }

    private function throttleKey(): string
    {
        return Str::lower("otp|" . $this->email . "|" . $this->ip());

    }
}
