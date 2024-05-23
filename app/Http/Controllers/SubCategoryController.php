<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use App\Services\PaginationService;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{


    public function index(Category $category, PaginationService $paginator, GetResourcesRequest $request)
    {

        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate($category->subCategories);

        return [
            'message' => "succeed",
            "data" => ["resources" => SubCategoryResource::collection($data), "discount" => $category->discount],
            "meta" => ["hasNextPage" => $hasNext],
            'code' => 200
        ];
    }

    public function show(SubCategory $subcategory)
    {
        $subcategory->load(['category', 'discount']);
        return [new SubCategoryResource($subcategory)];
    }

    public function store(Category $category, StoreSubCategoryRequest $request)
    {
        $subcategory_name = $request->post('name');
        $newSubcategories = SubCategory::create([
            'name' => $subcategory_name,
            'slug' => $request->post('slug') ?? Str::lower($subcategory_name),
            'category_id' => $category->id
        ]);

        return response([
            'message' => ucfirst($newSubcategories->name) . " subcategory created successfully",
            'data' => ['resource' => new SubCategoryResource($newSubcategories)],
            'code' => 201
        ], 201);

    }

    public function update(SubCategory $subcategory, UpdateSubcategoryRequest $request)
    {
        // TODO: refactor later
        $subcategory->update([...$request->only([
            'name',
            'discount_id',
            'category_id'
        ]),
            'slug' => $request->post('slug') ?? Str::lower($request->post('name'))
        ]);

        return [
            'message' => "Subcategory updated successfully",
            'data' => ['resource' => new SubCategoryResource($subcategory)],
            'code' => 200
        ];
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();
        return [
            'message' => $subcategory->name . " deleted successfully",
            'data' => ['id' => $subcategory->id],
            'code' => 200
        ];
    }
}
