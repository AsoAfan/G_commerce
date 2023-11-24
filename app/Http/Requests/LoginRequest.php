<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
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
            'message' => $this->messages(),
            'errors' => $validator->errors()->all(),
        ], 422));
    }

    function authenticate()
    {


        if (!Auth::attempt($this->only(['email', 'password']))) {
            return response(['message' => "Invalid credentials"], 400);
        }

        return [
            'message' => "Login Succeed",
            'data' => [
                "token" => Auth::user()->createToken('API Token')->plainTextToken,
                'user' => Auth::user()
            ],
        ];
    }
}
