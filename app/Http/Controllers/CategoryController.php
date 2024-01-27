<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {

        $newCategory = Category::create([
            'name' => $request->post('name'),
            'slug' => $request->post('slug'),
            'discount_id' => $request->post('discount_id') ?: null
        ]);

        return response(['message' => $newCategory->name . " created successfully", 'code' => 201], 201);

    }
}
