<?php

namespace App\Services\PublicSettings;

use App\Models\Core\Page;
use App\Services\Core\BaseService;

class PageService extends BaseService
{
    public function __construct()
    {
        $this->model = Page::class;
    }

}
