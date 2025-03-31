<?php

namespace Modules\Admins\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Admins\App\Models\Admin;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'country_code' => '966',
            'phone' => $this->faker->unique()->numberBetween(500000000, 599999999),
            'password' => bcrypt('password'), // Always use bcrypt
            'type' => $this->faker->randomElement(['admin', 'super_admin']),
//            'avatar' => null,
//            'is_blocked' => false,
//            'is_notify' => true,
//            'role_id' => null,
            'remember_token' => null,
        ];
    }
}

