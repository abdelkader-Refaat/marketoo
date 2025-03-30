<?php

namespace Modules\Admins\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admins\App\Models\Admin;

class AdminsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'name' => 'Manager',
            'email' => 'a@a.com',
            'phone' => '551111111',
            'password' => 123456,
            'type' => 'super_admin',
            'country_code' => '966',
        ]);
        if (!config('app.is_production')) {
            Admin::factory(10)->create();
        }
    }
}
