<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreSubCategoryRequest;
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

    public function store(StoreSubCategoryRequest $request)
    {

        $newSubcategories = SubCategory::create([
            'name' => $request->post('name'),
            'slug' => $request->post('slug') ?? Str::lower($request->post('name')),
            'category_id' => $request->post('category_id')
        ]);

        return response([
            'message' => ucfirst($newSubcategories->name) . " subcategory created successfully",
            'data' => ['id' => $newSubcategories->id],
            'code' => 201
        ], 201);

    }
}
