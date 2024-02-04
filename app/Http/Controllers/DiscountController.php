<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Services\PaginationService;

class DiscountController extends Controller
{

    public function index(PaginationService $paginationService)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginationService->paginate(Discount::query());
//        dd($data);
//        return DiscountResource::collection($discounts);

//        dd($data);
//        return $data;
//        return count($data);


        return [
            'message' => "success",
            'data' => DiscountResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    function store(StoreDiscountRequest $request)
    {

        $newDiscount = Discount::create([
            'ratio' => $request->post('ratio'),
            'starts_at' => $request->post('starts_at') ?: now(),
            'expires_at' => $request->post('expires_at')
        ]);

        return response([
            'message' => "Discount created successfully",
            'data' => ['id' => $newDiscount->id],
            'code' => 201
        ], 201);

    }
}
