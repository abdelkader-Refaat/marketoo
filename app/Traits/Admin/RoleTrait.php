<?php

namespace App\Traits\Admin;

use Illuminate\Support\Facades\Route;
use App\Models\PublicSettings\Permission;


trait  RoleTrait
{
    public function prepareDataForCreateAction()
    {
        $routes = Route::getRoutes();
        $routes_data = [];
        foreach ($routes as $route) {
            if ($route->getName()) {
                $routes_data['"' . $route->getName() . '"'] =
                    ['title' => isset($route->getAction()['title']) ?
                        $route->getAction()['title'] :
                        null];
            }
        }
        return ['routes_data' => $routes_data, 'routes' => $routes];
    }

    public function prepareDataForEditAction()
    {
        $routes         = Route::getRoutes();
        $routes_data    = [];
        $my_routes      = Permission::where('role_id', request()->id)->pluck('permission')->toArray();

        foreach ($routes as $route) {
            if ($route->getName()) {
                $routes_data['"' . $route->getName() . '"'] = ['title' => isset($route->getAction()['title']) ? $route->getAction()['title'] : null];
            }
        }
        return ['routes_data' => $routes_data, 'routes' => $routes, 'my_routes' => $my_routes];
    }
}
