<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Models\Brand;

class BrandController extends Controller
{
    public function store(StoreBrandRequest $request)
    {

        $newBrand = Brand::create([
            'name' => $request->post('name')
        ]);

        return response([
            'message' => ucfirst($newBrand->name) . " brand created successfully",
            'data' => $newBrand->id,
            'code' => 201
        ], 201);

    }

}
