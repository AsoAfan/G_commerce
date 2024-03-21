<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\PaginationService;
use Illuminate\Support\Str;

class CategoryController extends Controller
{


    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Category::query(), 'discount');

        return [
            'message' => "succeed",
            'data' => CategoryResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }


    public function show(Category $category)
    {
        // TODO: Generalize response into reusable response structure
        $category->loadCount('products');
        return [new CategoryResource($category)];

    }

    public function store(StoreCategoryRequest $request)
    {
        $newCategory = Category::create([
            'name' => $request->post('name'),
            'slug' => $request->post('slug') ?: Str::slug($request->post('name')),
            'image_path' => $request->post('image_path'),
            'image_name' => $request->post('image_name'),

            'discount_id' => $request->post('discount_id')
        ]);

        return response([
            'message' => $newCategory->name . " category created successfully",
            'data' => ['resource' => $newCategory],
            'code' => 201,
        ], 201);

    }

    public function update(Category $category, UpdateCategoryRequest $request)
    {
        // TODO: Refactor update statement
        $category->update([
            'name' => $request->post('name') ?: $category->name,
            'slug' => $request->post('slug') ?: Str::slug($request->post('name') ?: $category->name),
            'image_path' => $request->post('image_path') ?: $category->image_path,
            'image_name' => $request->post('image_name') ?: $category->image_name,
            'discount_id' => $request->post('discount_id') ?: $category->discount_id
        ]);
        return [
            'message' => "Category updated successfully",
            'data' => ['resource' => new CategoryResource($category)],
            'code' => 200
        ];

    }

    public function destroy(Category $category)
    {
        $category->delete();
        return [
            "message" => $category->name . " deleted successfully",
            'data' => ['id' => $category->id],
            'code' => 200];
    }

}
