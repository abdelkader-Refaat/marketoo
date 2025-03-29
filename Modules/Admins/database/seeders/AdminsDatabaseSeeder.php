<?php

namespace Modules\Admins\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admins\app\Models\Admin;

class AdminsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'     => 'Manager',
            'email'    => 'aait@info.com',
            'phone'    => '0555105813',
            'password' => 123456,
            'type'     => 'super_admin',
        ]);    }
}
