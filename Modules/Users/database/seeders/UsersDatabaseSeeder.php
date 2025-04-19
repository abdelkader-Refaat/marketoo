<?php

namespace Modules\Users\database\seeders;

use App\Models\Country;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\Users\App\Models\User;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ar_SA');
        $saudiArabiaNums = $faker->unique()->numberBetween(500000000, 599999999);

        $users = [];
        $country = Country::has('cities')->inRandomOrder()->first();
        User::create([
            'name' => 'Abdekader Refaat',
            'phone' => '551111111',
            'avatar' => 'ar.png',
            'email' => 'abdelkaderrefaat@gmail.com',
            'password' => 'password',
            'country_id' => $country?->id,
            'city_id' => $country?->cities()->inRandomOrder()->first()?->id,
            'is_blocked' => 0,
            'is_active' => 1,
            'created_at' => now(),
        ]);
        for ($i = 0; $i < 20; $i++) {
            if (!$country) {
                continue;
            }
            $users[] = [
                'name' => $faker->name,
                'phone' => $saudiArabiaNums,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'country_id' => $country->id,
                'city_id' => $country->cities()->inRandomOrder()->first()->id ?? null,
                'is_blocked' => rand(0, 1),
                'is_active' => rand(0, 1),
                'created_at' => now(),
            ];
        }
        User::insert($users);
    }
}
