<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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

        $id = $this->route('product')->id;
        return [
            "name" => ["string", "unique:products,name,$id"],
            'description' => ['min:10'],

            "attributes.*.price" => ['numeric'],
            "attributes.*.currency" => ['string', 'max:3'],
            "attributes.*.image_name" => ['required_with:image_path'],
            "attributes.*.quantity" => ['numeric'],


            'brand_id' => 'exists:brands,id',
            'category_id' => 'exists:categories,id',
            'discount_id' => 'exists:discounts,id',

            'sub_category_ids' => ['array', 'exists:sub_categories,id'],
            'group_ids' => ['array', 'exists:groups,id']

        ];
    }

    public function getProductAttributes(Product $product): array
    {
        $syncData = collect($this->post('attributes'))?->mapWithKeys(function ($attribute, $index) use ($product) {
            $existingAttribute = $product->attributes->get($index);
            return [
                $attribute['id'] => [
                    'value' => $attribute['value'] ?? $existingAttribute->pivot->value,
                    "display_type" => $attribute['display_type'] ?? $existingAttribute->pivot->display_type,
                    'price' => $attribute['price'] ?? $existingAttribute->pivot->price,
                    'currency' => $attribute['currency'] ?? $existingAttribute->pivot->currency,
                    'image_path' => $attribute['image_path'] ?? $existingAttribute->pivot->image_path,
                    'image_name' => $attribute['image_name'] ?? $existingAttribute->pivot->image_name,
                    'quantity' => $attribute['quantity'] ?? $existingAttribute->pivot->quantity,
                ]
            ];
        });

        return $syncData->toArray();
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
