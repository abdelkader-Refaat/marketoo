<?php

namespace App\Services\PublicSections;

use App\Services\Core\BaseService;
use App\Models\PublicSections\Intro;

class IntroService extends BaseService
{
    public function __construct()
    {
        $this->model = Intro::class;
    }

}
