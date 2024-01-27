<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    //

    function store(StoreDiscountRequest $request)
    {

        Discount::create([
            'ratio' => $request->post('ratio'),
            'starts_at' => $request->post('starts_at') ?: now(),
            'expires_at' => $request->post('expires_at')
        ]);

        return response([
            'message' => "Discount created successfully",
            'code' => 201
        ], 201);

    }
}
