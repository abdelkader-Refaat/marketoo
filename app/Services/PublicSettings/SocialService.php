<?php

namespace App\Services\PublicSettings;

use App\Services\Core\BaseService;
use App\Models\PublicSettings\Social;

class SocialService extends BaseService
{
    public function __construct()
    {
        $this->model = Social::class;
    }

}
