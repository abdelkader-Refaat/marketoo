<?php

namespace App\Http\Controllers\Admin\PublicSettings;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Models\PublicSettings\LogActivity;
use App\Services\PublicSettings\ReportService;

class ReportController extends AdminBasicController
{

    public function __construct()
    {
        $this->model = LogActivity::class;
        $this->directoryName = 'public-settings.reports';
        $this->serviceName = new ReportService();
        $this->indexScopes = 'search';
    }
}
