<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
//        return auth()->user()->role == 'admin';
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('brand')->id;

        return [
            'name' => ["unique:brands,name,$id"],
            'logo_name' => ['required_with:logo_path']
        ];
    }
}
