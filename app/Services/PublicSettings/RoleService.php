<?php

namespace App\Services\PublicSettings;

use App\Services\Core\BaseService;
use App\Models\PublicSettings\Role;

class RoleService extends BaseService
{
    public function __construct()
    {
        $this->model = Role::class;
    }

}
