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
        return auth()->user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', "string", 'unique:products,name'],
            'description' => ['required', 'min:10'],

            'brand_id' => 'exists:brands,id',
            'category_id' => 'exists:categories,id',
            'discount_id' => 'exists:discounts,id',

            'sub_category_ids' => ['array', 'exists:sub_categories,id'],
            'group_ids' => ['array', 'exists:groups,id'],

            'attributes' => ['array'],
            "attributes.*.quantity" => ['numeric'],
            "attributes.*.price" => ['required', 'numeric'],
            "attributes.*.currency" => ['string', 'max:3'],
            "attributes.*.image_path" => ['required'],
            "attributes.*.image_name" => ['required_with:image_path'],
        ];
    }

    public function overallQuantity()
    {
        return collect($this->post('attributes'))->sum(fn($attribute) => $attribute['quantity'] ?? 0);
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
