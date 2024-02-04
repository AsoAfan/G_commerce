<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\PaginationService;

class GroupController extends Controller
{

    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Group::query());

        return ['message' => "succeed", 'data' => new GroupResource($data), 'meta' => ['hasNextPage' => $hasNext], 'code' => 200];

    }

    public function store(StoreGroupRequest $request)
    {
        $newGroup = Group::create([

            'name' => $request->post('name'),
            'discount_id' => $request->post('discount_id'),
            'end_date' => $request->post('end_date')
        ]);

        $newGroup->products()->attach($request->post('products'));

        return response([
            'message' => $newGroup->name . " group created successfully",
            'data' => $newGroup->id,
            'code' => 201
        ], 201);
    }
}
