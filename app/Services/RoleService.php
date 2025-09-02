<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class RoleService extends BaseService
{
    public function index(): \Illuminate\Contracts\Pagination\Paginator
    {
        return Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->simplePaginate(15);
    }

    public function store(array $data): Role
    {
        $role = $this->tx(fn () => Role::create($data));
        Cache::forget('roles.index');
        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        $this->tx(fn () => $role->update($data));
        Cache::forget('roles.index'); 
        return $role->refresh();
    }

    public function destroy(Role $role): void
    {
        $this->tx(fn () => $role->delete());
        Cache::forget('roles.index'); 
    }
}
