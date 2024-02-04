<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function store(StoreLocationRequest $request)
    {
        $newLocation = Location::create([
            'city' => $request->post('city'),
            'longitude' => $request->post('longitude'),
            'latitude' => $request->post('latitude'),
            'type' => $request->post('type'),
            'note' => $request->post('note'),
            'user_id' => Auth::id()
        ]);

    }
}
