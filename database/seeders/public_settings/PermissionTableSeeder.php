<?php

namespace Database\Seeders\public_settings;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use App\Models\PublicSettings\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routes_data    = [];
        foreach (Route::getRoutes() as $route) {
            if ($route->getName()) {
                $routes_data[]   = ['role_id' => 1, 'permission' => $route->getName()];
            }
        }
        Permission::insert($routes_data);
    }
}
