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
        // Create main admin
        Admin::create([
            'name' => 'Manager',
            'avatar' => 'ar.png',
            'email' => 'abdelkaderrefaat@gmail.com',
            'country_code' => '966',
            'phone' => '551111111',
            'password' => bcrypt('password'),
            'type' => 'super_admin',
        ]);

        // Create 10 test admins (only in non-production)
        if (!app()->isProduction()) {
            Admin::factory()->count(10)->create();
        }
    }
}
