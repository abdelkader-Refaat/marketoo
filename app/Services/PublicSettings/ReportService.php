<?php

namespace App\Services\PublicSettings;

use App\Services\Core\BaseService;
use App\Models\PublicSettings\LogActivity;

class ReportService extends BaseService
{
    public function __construct()
    {
        $this->model = LogActivity::class;
    }

}
