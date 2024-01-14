<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
//        return Product::all();
        return ProductResource::collection(Product::all());
    }



    public function store(StoreProductRequest $request)
    {

//        dd($request->all(), $request->except('attributes'));
        $newProduct = Product::create([
            "name" => $request->post('name'),
            'description' => $request->post('description'),
            "price" => $request->post('price'), // TODO: Refactor later
            "currency" => $request->post('currency'),
            "image_path" => $request->post('image_path'), // TODO: Refactor later
            "image_name" => $request->post('image_name'), // TODO: Refactor later

            "brand_id" => $request->post('brand_id'),
            "category_id" => $request->post('category_id'),
            'discount_id' => $request->post('discount_id')
            // TODO: Update api_docs and test this code
//            "group_id" => $request->post('group_id')

        ]);

        $newProduct->group()->attach($request->post('group_ids'));
        $newProduct->subCategories()->attach($request->post('sub_category_ids'));

//        TODO: can be better(no loop)?

//        ['color' : 'red']
        foreach ($request->post('attributes') as $attribute) {
//            dd($attribute);
            $newAttribute = Attribute::where('name', key($attribute))->first();

            if (! $newAttribute) $newAttribute = Attribute::create(['name' => key($attribute)]);

            $newProduct->attributes()->attach($newAttribute, [
                'value' => $attribute[key($attribute)],
                'display_type' => $attribute['display_type']
            ]);
        }


        return ['message' => Str::substr($newProduct->name, 0, 12) . " Added successfully", 'code' => 200];
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
