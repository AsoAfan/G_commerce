<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\PaginationService;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{


    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Auth::user()->locations);
        return [
            'message' => 'succeed',
            'data' => LocationResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];
    }

    /*
    public function show(Location $location)
    {
        return [new LocationResource(Auth::user()->locations)];
    }
    */

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

        return response([
            'message' => "Location added successfully",
            'data' => ['resource' => $newLocation],
            'code' => 201
        ], 201);

    }

    public function update(Location $location, UpdateLocationRequest $request)
    {

        $location->update($request->only(['city', 'type', 'longitude', 'latitude', 'note']));

        return [
            'message' => "Location updated successfully",
            'data' => ['resource' => new LocationResource($location)],
            'code' => 200
        ];

    }

    public function destroy(Location $location)
    {

        $this->authorize('delete', $location);

        $location->delete();
        return [
            'message' => ucfirst($location->city) . " removed",
            'data' => ['id' => $location->id],
            'code' => 200
        ];
    }
}
