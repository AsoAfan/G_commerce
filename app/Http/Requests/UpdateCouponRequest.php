<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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

        $id = $this->route('coupon')->id;

        // TODO: re-check for date validations for dates

        return [
            'code' => "min:3|unique:coupons,code,$id",
            'ratio' => 'numeric',
            'starts_at' => 'date',
            'expires_at' => ['date', 'after:starts_at']
        ];
    }
}
