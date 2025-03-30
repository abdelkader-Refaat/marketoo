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
        $faker = \Faker\Factory::create('ar_SA');
        $saudiArabiaNums = $faker->unique()->numberBetween(500000000, 599999999);
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'country_code' => '966',
            'phone' => $saudiArabiaNums,
            'password' => 123456,
        ];
    }
}

