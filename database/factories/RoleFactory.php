<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement(['Manager', 'Member', 'Viewer']);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'daily_credits' => $this->faker->numberBetween(20, 200),
            'meta' => ['color' => $this->faker->safeHexColor()],
        ];
    }
}
