<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\PaginationService;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{

    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Brand::query()->withCount('products'));

        return [
            'message' => "succeed",
            'data' => BrandResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    public function show($id)
    {
        DB::enableQueryLog();
        $brand = Brand::where('id', $id)->first();

        if (!$brand) return missingRoute();

        return new BrandResource($brand);
    }

    public function store(StoreBrandRequest $request)
    {

        $newBrand = Brand::create([
            'name' => $request->post('name'),
            'logo_path' => $request->post('logo_path'),
            'logo_name' => $request->post('logo_name')
        ]);

        return response([
            'message' => ucfirst($newBrand->name) . " brand created successfully",
            'data' => ['id' => $newBrand->id],
            'code' => 201
        ], 201);

    }

    public function update(Brand $brand, UpdateBrandRequest $request)
    {
//        dd($request->all());

        $brand->update($request->all());
        return [
            'message' => "Brand updated successfully",
            'data' => ['resource' => new BrandResource($brand)],
            'code' => 200
        ];

    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return [
            "message" => $brand->name . " deleted successfully",
            'data' => ['id' => $brand->id],
            'code' => 200];
    }

}
