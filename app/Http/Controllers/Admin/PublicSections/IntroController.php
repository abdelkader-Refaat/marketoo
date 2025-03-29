<?php

namespace App\Http\Controllers\Admin\PublicSections;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Models\PublicSections\Intro;
use App\Http\Requests\Admin\PublicSections\Intros\StoreRequest;
use App\Http\Requests\Admin\PublicSections\Intros\UpdateRequest;
use App\Services\PublicSections\IntroService;

class IntroController extends AdminBasicController
{
    public function __construct()
    {
        $this->model = Intro::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = UpdateRequest::class;
        $this->directoryName = 'public-sections.intros';
        $this->serviceName = new IntroService();
        $this->indexScopes = 'search';
    }
}
