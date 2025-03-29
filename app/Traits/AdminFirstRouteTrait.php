<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use App\Models\PublicSettings\Permission;

trait AdminFirstRouteTrait
{
  public function getAdminFirstRouteName($authRoutes = null)
  {
    $routeName = 'intro';
    // $first_route = '';


    if (auth('admin')->check() && auth('admin')->user()->type == 'super_admin') {
      $routeName = 'admin.dashboard';
    }

    if (!$authRoutes) {
      $authRoutes = Permission::where('role_id', auth()->guard('admin')->user()->role_id)->pluck('permission')->toArray();
    }

    $routes = Route::getRoutes('web');
    foreach ($routes as $route) {

      if (in_array($route->getName(), $authRoutes) &&
        (isset($route->getAction()['icon']) || (isset($route->getAction()['sub_link'])
          && $route->getAction()['sub_link'] == true)) &&
        (!isset($route->getAction()['sub_route']) || $route->getAction()['sub_route'] == false)
        && $route->getAction()['uses']
      ) {
        // $routeName = $route->getName();
        $first_route = $route;
        break;
      }
    }

    return isset($first_route) && $first_route?->getAction()['uses'] ? $route->getName() : 'admin.dashboard';
  }
}
