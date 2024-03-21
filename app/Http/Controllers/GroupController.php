<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetResourcesRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Services\PaginationService;

class GroupController extends Controller
{

    public function index(PaginationService $paginator, GetResourcesRequest $request)
    {
        ['data' => $data, 'hasNextPage' => $hasNext] = $paginator->paginate(Group::query()->withCount('products'));

        return [
            'message' => "succeed",
            'data' => GroupResource::collection($data),
            'meta' => ['hasNextPage' => $hasNext],
            'code' => 200
        ];

    }

    public function show(Group $group)
    {
        $group->load('products', 'discount');
        return [new GroupResource($group)];

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
            'data' => ['resource' => $newGroup],
            'code' => 201
        ], 201);
    }

    public function update(Group $group, UpdateGroupRequest $request)
    {
        $group->update($request->only(['name', 'discount_id', 'end_date']));
        $group->products()->syncWithoutDetaching($request->post('products'));
        $group->load('products');
        return [
            'message' => "Group updated successfully",
            'data' => ['resource' => new GroupResource($group)],
            'code' => 200
        ];
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return [
            'message' => $group->name,
            'data' => ['id' => $group->id],
            'code' => 200
        ];

    }
}
