<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//        return auth()->role == 'admin'; // TODO: Only Admins
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
            "name" => ['required', "string"],
            'description' => ['required', 'min:10'],
            "price" => ['required', 'numeric'], // TODO: Refactor later
            "currency" => ['string', 'max:3'],
            "image_path" => ['required'], // TODO: Refactor later
            "image_name" => ['required_with:image_path' , "unique:products,image_name"], // TODO: Refactor later

            'brand_id'=>'exists:brands,id',
            'category_id'=>'exists:categories,id',
            'discount_id' => 'exists:discounts,id',

            'sub_category_ids' => ['array', 'exists:sub_categories,id'],
            'group_ids' => ['array','exists:groups,id'],

            'attributes' => ['array']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => 'Validation failed',
            'errors' => $validator->errors()->all(),
            'code' => 422
        ], 422)
        );

    }


}
