<?php

namespace Modules\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faker = Factory::create('ar_SA');

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->unique()->numerify('5########'),
            'password' => Hash::make('password'),
            'is_blocked' => rand(0, 1),
            'active' => rand(0, 1),
            'created_at' => now(),
        ];    }
}

