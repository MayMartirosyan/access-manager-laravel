<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function index(array $filters = []): Collection
    {
        $key = 'users.index.' . md5(json_encode($filters));

        return Cache::remember($key, now()->addMinutes(5), function () use ($filters) {
            return User::query()
                ->with(['roles:id,name,slug'])
                ->when(
                    $filters['role'] ?? null,
                    fn($q, $roleSlug) =>
                        $q->whereHas('roles', fn($qq) => $qq->where('slug', $roleSlug))
                )
                ->when($filters['search'] ?? null, function ($q, $search) {
                    $q->where(function ($qq) use ($search) {
                        $qq->where('name', 'like', "%$search%")
                           ->orWhere('email', 'like', "%$search%");
                    });
                })
                ->orderBy('name')
                ->get();
        });
    }

    public function store(array $data): User
    {
        $roleIds = $data['role_ids'];
        unset($data['role_ids']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->tx(function () use ($data, $roleIds) {
            $user = User::create($data + [
                'credits_remaining' => 100,
                'last_credits_reset_at' => now(),
            ]);
            $user->roles()->sync($roleIds);
            return $user;
        });

        Cache::forget('users.index');
        return $user->load('roles');
    }

    public function update(User $user, array $data): User
    {
        $roleIds = $data['role_ids'] ?? null;
        unset($data['role_ids']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $this->tx(function () use ($user, $data, $roleIds) {
            $user->update($data);
            if ($roleIds !== null) {
                $user->roles()->sync($roleIds);
            }
        });

        Cache::forget('users.index');
        return $user->load('roles');
    }

    public function destroy(User $user): void
    {
        $this->tx(fn() => $user->delete());
        Cache::forget('users.index');
    }
}
