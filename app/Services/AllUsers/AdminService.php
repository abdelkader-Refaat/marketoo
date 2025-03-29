<?php

namespace App\Services\AllUsers;

use App\Models\AllUsers\Admin;
use App\Services\Core\BaseService;

class AdminService extends BaseService
{
    protected $model;

    public function __construct()
    {
        $this->model = \Modules\Users\Models\Admin::class;
    }
}
