<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\PaginationService;

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

    public function store(StoreCategoryRequest $request)
    {

        $newCategory = Category::create([
            'name' => $request->post('name'),
            'slug' => $request->post('slug'),
            'discount_id' => $request->post('discount_id') ?: null
        ]);

        return response([
            'message' => $newCategory->name . " category created successfully",
            'data' => ['id' => $newCategory->id],
            'code' => 201,
        ], 201);

    }
}
