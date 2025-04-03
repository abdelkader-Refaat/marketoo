<?php
namespace Database\Seeders\public_settings;

use Illuminate\Database\Seeder;
use App\Models\PublicSettings\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role ::create([
            'name' => ['ar' => 'ادمن' , 'en' => 'admin']
        ]);
    }
}
