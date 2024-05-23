<?php

namespace App\Http\Requests;

use App\Exceptions\TooManyRequestsException;
use App\Http\Resources\UserResource;
use App\Mail\LoginAttemptMail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    private $maxAttempts = 5;


    protected function prepareForValidation()
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => $this->messages() ?: "Login failed",
            'errors' => $validator->errors()->all(),
            'code' => 422
        ], 422));
    }

    function authenticate()
    {

        if (!Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            return response(['message' => "Invalid credentials", 'code' => 400], 400);
        }

        return [
            'message' => "Login Succeed",
            'code' => 200,

            'data' => [
                "token" => Auth::user()->createToken($this->device_name())->plainTextToken,
                'user' => new UserResource(Auth::user()),
            ],

        ];
    }

    /**
     * @throws TooManyRequestsException
     */
    public function makeSureNotRateLimited()
    {

        RateLimiter::hit($this->throttleKey(), 600);

        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts))
            return;

        if (RateLimiter::attempts($this->throttleKey()) == $this->maxAttempts)
            Mail::to($this->email)->send(new LoginAttemptMail($this->email));

        throw new TooManyRequestsException();
    }

    public function device_name()
    {
        //                                      TODO: change later
        return $this->device_name ?: $this->email . $this->ip();
    }

    private function throttleKey()
    {
        return Str::lower("log|" . $this->email . "|" . $this->ip());
    }

}
