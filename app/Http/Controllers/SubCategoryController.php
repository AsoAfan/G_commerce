<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Resources\SubCategoryResource;
use App\Models\Category;
use App\Models\SubCategory;
use App\Services\PaginationService;

class SubCategoryController extends Controller
{

    public function index(Category $category, PaginationService $paginator, GetResourcesRequest $request)
    {

        $subs = $category->subCategories;
        return SubCategoryResource::collection($subs);

    }

    public function store(StoreSubCategoryRequest $request)
    {

        $newSubcategories = SubCategory::create([
            'name' => $request->post('name'),
            'slug' => $request->post('slug'),
            'category_id' => $request->post('category_id')
        ]);

        return response([
            'message' => ucfirst($newSubcategories->name) . " subcategory created successfully",
            'data' => ['id' => $newSubcategories->id],
            'code' => 201
        ], 201);

    }
}
