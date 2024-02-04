<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//        return auth()->user()->role == 'admin';
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
            'name' => ['required', 'string'],
            'discount_id' => ['required', 'exists:discounts,id'],
            'end_date' => 'date',
            'products' => ['required', 'array', 'exists:products,id']
        ];
    }

    // TODO: Refactor to avoid duplication later
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => 'Validation failed',
            'errors' => $validator->errors()->all(),
            'code' => 422
        ], 422));
    }

}
