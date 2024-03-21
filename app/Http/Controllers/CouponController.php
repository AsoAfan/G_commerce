<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCouponRequest;
use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use App\Services\PaginationService;

class CouponController extends Controller
{

    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Coupon::query());

        return [
            'message' => 'succeed',
            'data' => CouponResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    public function show(Coupon $coupon, GetCouponRequest $request)
    {
        /*
         * implementing coupons for limited users:
         * 1. inserting user ids as an array with coupon info into database table, then using those ids to check if the user is valid for that coupon
         * 2. using constant types of users(freshers, experienced-users, active-users, ...etc.) based on user joined date and activity
         * ? */

        return [
            'message' => "Coupon applied successfully",
            'data' => ['ratio' => $coupon->ratio],
            'code' => 200
        ];
    }

    public function store(StoreCouponRequest $request)
    {

        $newCoupon = Coupon::create([
            'code' => $request->post('code'),
            'ratio' => $request->post('ratio'),
            'starts_at' => $request->post('starts_at') ?? now(),
            'expires_at' => $request->post('expires_at') ?? now()->addWeek()
        ]);


        return response([
            'message' => "Coupon created successfully",
            'data' => ['resource' => $newCoupon],
            'code' => 201
        ], 201);

    }

    public function update(Coupon $coupon, UpdateCouponRequest $request)
    {
        $coupon->update($request->only(['code', 'ratio', 'starts_at', 'expires_at']));

        return [
            'message' => "Coupon updated successfully",
            'data' => ['resource' => new CouponResource($coupon)],
            'code' => 200
        ];
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return [
            "message" => $coupon->name . " deleted successfully",
            'data' => ['id' => $coupon->id],
            'code' => 200
        ];
    }


}
