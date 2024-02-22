<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Rating;
use App\Services\PaginationService;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{

    public function index(PaginationService $paginationService, GetResourcesRequest $request)
    {
//        return Product::query()->filter([]);

//        dd($request->input('$test'));
//        DB::enableQueryLog();
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(

            Product::query()
            , ['attributes', 'ratings', 'brand', 'discount']);

//        return (DB::getQueryLog());


        return [
            'message' => 'succeed',
            'data' => ProductResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    public function search(PaginationService $paginationService, SearchRequest $request)
    {

        if (empty($request->all())) return "Nothing";

        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(

            Product::query()
                ->filter($request->only(['s', 'attributes', 'brand', 'category', 'subcategory', 'groups']))
                ->withCount('ratings')
            , ['ratings', 'brand', 'discount', 'subCategories']);

//        return (DB::getQueryLog());

        return [
            'message' => 'succeed',
            'data' => ProductResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];

    }


    public function store(StoreProductRequest $request)
    {

//        dd($request->all(), $request->except('attributes'));
        $newProduct = Product::create([
            "name" => $request->post('name'),
            'description' => $request->post('description'),

            "brand_id" => $request->post('brand_id'),
            "category_id" => $request->post('category_id'),
            'discount_id' => $request->post('discount_id')
        ]);


        $newProduct->groups()->attach($request->post('group_ids'));
        $newProduct->subCategories()->attach($request->post('sub_category_ids'));

//        TODO: can be better(no loop)?
        /*
         *
         * [
         *  "jsjs": sih
         *
         * */
//        ['color' : 'red']
        foreach ($request->post('attributes') as $attribute) {
//            dd($attribute);
            $newAttribute = Attribute::firstOrCreate(['name' => key($attribute)]);
//            $newAttribute = Attribute::where('name', key($attribute))->first();

//            if (! $newAttribute) $newAttribute = Attribute::create(['name' => key($attribute)]);

            $newProduct->attributes()->attach($newAttribute, [
                'value' => $attribute[key($attribute)],
                'display_type' => $attribute['display_type'],
                'image_path' => $attribute['image_path'],
                'image_name' => $attribute['image_name'],
                'price' => $attribute['price'],
                'currency' => $attribute['currency'],
                'quantity' => $attribute['quantity'],
            ]);
        }
        return response([
            'message' => firstWord($newProduct->name) . " Added successfully",
            'code' => 201
        ], 201);
    }


    public function rate(Product $product, StoreRatingRequest $request)
    {

        $rating = Rating::create([
            'rating' => $request->post('rating'),
            'review' => $request->post('review')
        ]);

        $product->ratings()->attach($rating);
        return response([
            'message' => "Thank you for your feedback",
            'data' => ['id' => $rating->id],
            'code' => 200
        ]);
    }

    public function favourite(Product $product)
    {
//        return "HI";
//        dd(auth()->user()->products);

        $test = auth()->user()->products()->toggle($product);

//        return $test;

        return ['message' => firstWord($product->name) . ($test['attached'] ? " added to" : " removed from") . " favourite list", 'code' => 200];

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
//        dd($id);


        $product = Product::where('id', $id)->first();
        if (!$product)
            return missingRoute();

        $product->load(['discount', 'attributes', 'subCategories']);


        return [new ProductResource($product)];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->except(['attributes', 'group_ids', 'sub_category_ids']));

        $product->groups()->sync($request->post('group_ids'));
        $product->subCategories()->sync($request->post('sub_category_ids'));

        $attributes = $request->post('attributes');
        $values = [];

        foreach ($attributes as $attribute) {
            $key = key($attribute);
            $values[$key] = [
                'value' => $attribute[$key],
                "display_type" => $attribute['display_type'],
                'price' => $attribute['price'],
                'currency' => $attribute['currency'],
                'image_path' => $attribute['image_path'],
                'image_name' => $attribute['image_name'],
                'quantity' => $attribute['quantity'],
            ];
        }

        try {
            $product->attributes()->syncWithoutDetaching($values);
        } catch (QueryException) {
            return response([
                'message' => "Validation failed",
                'errors' => ['The selected attribute ids is invalid.'],
                'code' => 422
            ], 422);
        }

        $product->load('attributes');

        return [
            'message' => "Product updated successfully",
            'data' => ['resource' => new ProductResource($product)],
            'code' => 200
        ];

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return [
                'message' => "Product deleted successfully",
                'data' => ['id' => $product->id],
                'code' => 200
            ];
        } else {
            $code = response()->getStatusCode();
            return response(['message' => "An error occurred", 'code' => $code], $code);
        }

    }

    public function destroyPermanently(Product $product)
    {
        if ($product->forceDelete()) {
            return ['message' => "Product deleted successfully", 'code' => 200];
        } else {
            $code = response()->getStatusCode();
            return response(['message' => "An error occurred", 'code' => $code], $code);
        }
    }
}
