<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\Rating;
use App\Services\PaginationService;

class ProductController extends Controller
{

    public function index(PaginationService $paginationService, GetResourcesRequest $request)
    {
//        return Product::query()->filter([]);

//        dd($request->input('$test'));
//        DB::enableQueryLog();
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(

            Product::query()->filter(request(['s', 'attribute']))
                ->withCount('ratings')
            , ['ratings', 'brand', 'discount']);

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
                'display_type' => $attribute['display_type']
            ]);
        }
        return response([
            'message' => strtok($newProduct->name, ' ') . " Added successfully",
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
//        dd($id);


        $product = Product::where('id', $id)->first();
        if (!$product)
            return response(['message' => "Not found", 'code' => 404], 404);

        $product->with(['brand', 'discount']);
        return new ProductResource($product);
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
