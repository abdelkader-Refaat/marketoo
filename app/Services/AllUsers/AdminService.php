<?php

namespace App\Services\AllUsers;

use App\Services\Core\BaseService;
use Modules\Admins\App\Models\Admin;

class AdminService extends BaseService
{
    protected $model;

    public function __construct()
    {
        $this->model = Admin::class;
    }
}
