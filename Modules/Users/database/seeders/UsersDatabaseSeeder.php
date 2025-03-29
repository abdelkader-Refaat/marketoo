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
        $users = [];
            $country = Country::has('cities')->inRandomOrder()->first();
            User::create([
                'name'       => 'Abdekader Refaat',
                'phone'      => '551111111',
                'email'      => 'abdelkaderrefaat@gmail.com',
                'password'   => bcrypt('password'),
                'country_id' => $country?->id,
                'city_id'    => $country?->cities()->inRandomOrder()->first()?->id,
                'is_blocked' => 0,
                'active'     => 1,
                'created_at' => now(),
            ]);
        for ($i = 0; $i < 20; $i++) {
            if (!$country) continue;
            $users[] = [
                'name' => $faker->name,
                'phone' => "51111111$i",
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'), // Hashed password
                'country_id' => $country->id,
                'city_id' => $country->cities()->inRandomOrder()->first()->id ?? null,
                'is_blocked' => rand(0, 1),
                'active' => rand(0, 1),
                'created_at' => now(),
            ];
        }
        User::insert($users);    }
}
