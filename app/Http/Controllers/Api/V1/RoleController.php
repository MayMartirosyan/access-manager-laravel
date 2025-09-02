<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use App\Traits\ApiResponse;

class RoleController extends Controller
{
    use ApiResponse;

    public function __construct(private RoleService $roles)
    {
    }

    public function index()
    {
        $p = $this->roles->index();
        return RoleResource::collection($p);
    }

    public function store(RoleStoreRequest $request)
    {
        $role = $this->roles->store($request->validated());
        return $this->success(new RoleResource($role), 'Created', 201);
    }

    public function show(Role $role)
    {
        $role->loadCount('users');
        return $this->success(new RoleResource($role));
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        $role = $this->roles->update($role, $request->validated());
        return $this->success(new RoleResource($role), 'Updated');
    }

    public function destroy(Role $role)
    {
        $this->roles->destroy($role);
        return $this->success(null, 'Deleted', 204);
    }
}
