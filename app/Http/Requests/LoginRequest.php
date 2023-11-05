<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }

    function authenticate()
    {



        if (!Auth::attempt($this->only(['email', 'password']))) {
            return response(['message' => "Invalid credentials"], 400);
        }

        return [
            'message' => "Login Succeed",

            "token" => Auth::user()->createToken('API Token')->plainTextToken,

        ];


    }
}
