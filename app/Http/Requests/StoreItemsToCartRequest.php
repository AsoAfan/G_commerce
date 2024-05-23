<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreItemsToCartRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required',
            'products.*.id' => ['exists:products,id'],
            "products.*.attributes.*" => 'exists:values,value',
        ];
    }

    public function getQuantity()
    {

        return collect($this->products)->flatMap(fn($item) => [$item['id'] => $item['quantity']]);
    }

    public function getProducts()
    {
//        return $this->products;
        $ids = array_column($this->products, 'id');
        $quantities = array_column($this->products, 'quantity');
        $attributes = array_column($this->products, 'attributes');
//        dd($attributes);
        $attributes = collect($attributes)->map(fn($product) => array_keys($product))->toArray();
//        $result = $attributes->implode(',');
//        dd($attributes);
//        dd($attributes);
        return collect(array_map(function ($id, $quantity, $attribute) {
            return [
                'id' => $id,
                'quantity' => $quantity,
                'attributes' => $attribute
            ];
        }, $ids, $quantities, $attributes));

    }

    public function getAttributes()
    {
        return collect(collect($this->products)->reduce(function ($attributes, $product) {
            if (array_key_exists('attributes', $product))
                foreach ($product['attributes'] as $id => $value) {
                    $attributes[$id] = $value;
                }
            return $attributes;
        }, []));
    }


    public function attributes()
    {
        return [
            'products.*.id' => "product id",
            'products.*.attributes.*' => "product attributes",
        ];
    }

    public function messages()
    {
        return [
            'products.*.attributes.*.exists' => "invalid attribute value"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'message' => 'Validation failed',
            'errors' => $validator->errors()->all(),
            'code' => 422
        ], 422));
    }


}
