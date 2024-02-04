<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Services\PaginationService;

class CouponController extends Controller
{

    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Coupon::query());

        return ['message' => 'succeed', 'data' => new CouponResource($data), 'meta' => ['hasNextPage' => $hasNext], 'code' => 200];
    }

    public function store(StoreCouponRequest $request)
    {

        $newCoupon = Coupon::create([
            'code' => $request->post('code'),
            'ratio' => $request->post('ratio'),
            'starts_at' => $request->post('starts_at'),
            'expires_at' => $request->post('expires_at')
        ]);

        return response([
            'message' => "Coupon created successfully",
            'data' => ['id' => $newCoupon->id],
            'code' => 201
        ], 201);

    }
}
