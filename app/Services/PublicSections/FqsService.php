<?php

namespace App\Services\PublicSections;

use App\Services\Core\BaseService;
use App\Models\PublicSections\Fqs;

class FqsService extends BaseService
{
    public function __construct()
    {
        $this->model = Fqs::class;
    }

}
