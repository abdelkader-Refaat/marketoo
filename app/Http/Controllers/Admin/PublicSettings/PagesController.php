<?php

namespace App\Http\Controllers\Admin\PublicSettings;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Models\Core\Page;
use App\Http\Requests\Admin\Core\Pages\UpdateRequest;
use App\Services\PublicSettings\PageService;

class PagesController extends AdminBasicController
{
    public function __construct()
    {
        $this->model = Page::class;
        $this->updateRequest = UpdateRequest::class;
        $this->directoryName = 'pages';
        $this->serviceName = new PageService();
        $this->indexScopes = 'search';
    }
}
