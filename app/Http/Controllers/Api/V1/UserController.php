<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $users)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->only('role', 'search');
        $p = $this->users->index($filters);
        $p->load('roles');

        return $this->success(
             UserResource::collection($p),
        );
    }

    public function store(UserStoreRequest $request)
    {
        $user = $this->users->store($request->validated());
        return $this->success(new UserResource($user), 'Created', 201);
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user->load('roles')));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user = $this->users->update($user, $request->validated());
        return $this->success(new UserResource($user), 'Updated');
    }

    public function destroy(User $user)
    {
        $this->users->destroy($user);
        return $this->success(null, 'Deleted', 204);
    }
}