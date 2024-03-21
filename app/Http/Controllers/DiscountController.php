<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\PaginationService;

class DiscountController extends Controller
{

    public function index(PaginationService $paginationService, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(Discount::query());

        return [
            'message' => "succeed",
            'data' => DiscountResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }


    public function show(Discount $discount)
    {
        $discount->load('products');
        return [new DiscountResource($discount)];

    }


    public function store(StoreDiscountRequest $request)
    {

        $starts_at = $request->post('starts_at') ?: now();

        $newDiscount = Discount::create([
            'ratio' => $request->post('ratio'),
            'starts_at' => $starts_at,
            'expires_at' => $request->post('expires_at') ?: $starts_at->copy()->addWeek()
        ]);

        return response([
            'message' => "Discount created successfully",
            'data' => ['resource' => $newDiscount],
            'code' => 201
        ], 201);

    }

    public function update(Discount $discount, UpdateDiscountRequest $request)
    {
        $discount->update($request->only(['ratio', 'starts_at', 'expires_at']));
        return [
            'message' => "Discount updated successfully",
            'data' => ['resource' => new DiscountResource($discount)],
            'code' => 200
        ];

    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return [
            'message' => $discount->ratio . "% discount removed",
            'data' => ['id' => $discount->id],
            'code' => 200
        ];
    }

}
