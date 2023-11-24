<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @OA\Get(
     *     path="/api/products",
     *     summary="Returns all products with thier relations",
     *     description="Returns all products with associated categories and attributes",
     *     operationId="showProducts",
     *
     *     @OA\Response(
     *         response="200",
     *         description="All product retrieved successfully"
     *     )
     *
     * )
     *
     *
     */
    public function index()
    {
//        return Product::all();
        return ProductResource::collection(Product::all());
    }

    /**
     * @OA\Post(
     *     path="/product/store",
     *     operationId="Produc",
     *     summary="Create new product",
     *     description="Stores new product with its attributes into the database",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Test"
     *     )
     * )
     */

    public function store(StoreProductRequest $request)
    {

//        dd($request->all(), $request->except('attributes'));
        $p = Product::create([
            "name" => "product 1",
            "price" => "10",
            "currency" => "IQD",
            "image_path" => "path/to/image.jpg",

        ]);

        foreach ($request->get('attributes') as $attr) {
//            dd($attr);
            $attribute = Attribute::create(['name' => key($attr)]);
            $p->attributes()->attach($attribute, [
                'value' => $attr[key($attr)],
                'display_type' => $attr['display_type'] ?: "dropdown"
            ]);
        }


        return ProductResource::collection(Product::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
