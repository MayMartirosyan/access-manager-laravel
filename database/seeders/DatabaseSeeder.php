<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'daily_credits' => 0,
                'meta' => ['power' => 'all']
            ]
        );

        $memberRole = Role::firstOrCreate(
            ['slug' => 'member'],
            [
                'name' => 'Member',
                'daily_credits' => 100,
                'meta' => []
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'credits_remaining' => 100,
                'last_credits_reset_at' => now(),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        if (User::whereDoesntHave('roles', fn($q) => $q->where('slug', 'admin'))->count() < 5) {
            User::factory()->count(5)->create()->each(function (User $u) use ($memberRole) {
                $u->roles()->sync([$memberRole->id]);
            });
        }
        
        if (Content::count() == 0) {
            Content::factory()->count(10)->create();
        }
    }
}
