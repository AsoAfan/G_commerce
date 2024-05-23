<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\RatingResource;
use App\Models\Attribute;
use App\Models\Product;
use App\Services\PaginationService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(PaginationService $paginationService, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(
            Product::withCount('ratings')
            , ['attributes', 'ratings', 'brand', 'discount']
        );

        return [
            'message' => 'succeed',
            'data' => ProductResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    public function search(PaginationService $paginationService, SearchRequest $request)
    {

        if (empty($request->all())) return [];

        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(

            Product::query()
                ->filter($request->only(['s', 'attributes', 'brand', 'category', 'subcategory', 'groups']))
                ->withCount('ratings')
            , ['attributes', 'ratings', 'brand', 'discount', 'subCategories']);

//        return (DB::getQueryLog());

        return [
            'message' => 'succeed',
            'data' => ProductResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];

    }

    public function featured()
    {
        // TODO: paginate this if needed
        $products = Product::where('is_featured', 1)->with([
            'attributes' => fn($query) => $query->first(),
            'discount'

        ])->get();

        return [ProductResource::collection($products)];
    }

    public function store(StoreProductRequest $request)
    {


        $newProduct = Product::create([
            "name" => $request->post('name'),
            'description' => $request->post('description'),

            "price" => $request->post('price'),
            "quantity" => $request->overallQuantity(),

            "brand_id" => $request->post('brand_id'),
            "category_id" => $request->post('category_id'),
            'discount_id' => $request->post('discount_id')
        ]);


        $newProduct->groups()->attach($request->post('group_ids'));
        $newProduct->subCategories()->attach($request->post('sub_category_ids'));

        $productQuantity = 0;

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
                'currency' => $attribute['currency'] ?? "IQD",
                'quantity' => $attribute['quantity'] ?? 0,
            ]);
        }
        $newProduct->load('attributes');
        return response([
            'message' => firstWord($newProduct->name) . " Added successfully",
            'data' => ['resource' => new ProductResource($newProduct)],
            'code' => 201
        ], 201);
    }


    public function rate(Product $product, StoreRatingRequest $request)
    {
        $rating = $product->ratings()->create([
            'rating' => $request->post('rating'),
            'review' => $request->post('review'),
            'user_id' => Auth::id(),
        ]);
        $rating->load('user');
        return response([
            'message' => "Thank you for your feedback",
            'data' => ['resource' => new RatingResource($rating)],
            'code' => 200
        ]);
    }

    public function favourite(Product $product)
    {
        $fav = auth()->user()->products()->toggle($product);

//        return $fav;

        return ['message' => firstWord($product->name) . ($fav['attached'] ? " added to" : " removed from") . " favourite list", 'code' => 200];

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // TODO: paginate ratings later
        $product->load(['ratings', 'discount', 'attributes', 'subCategories'])
            ->loadCount('ratings');
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

        try {
            $product->load('attributes');
            $product->attributes()->syncWithoutDetaching($request->getProductAttributes($product));
        } catch (QueryException) {
            return response()->json([
                'message' => "Validation failed",
                'errors' => ['The selected attribute ids is invalid.'],
                'code' => 422
            ], 422);
        }


        return response()->json([
            'message' => "Product updated successfully",
            'data' => ['resource' => new ProductResource($product->refresh())],
            'code' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return [
            'message' => "Product deleted successfully",
            'data' => ['id' => $product->id],
            'code' => 200
        ];


    }

    public function destroyPermanently(Product $product)
    {
        $product->forceDelete();

        return [
            'message' => "Product deleted successfully",
            'data' => ['id' => $product->id],
            'code' => 200
        ];
    }
}
