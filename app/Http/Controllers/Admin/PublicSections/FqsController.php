<?php

namespace App\Http\Controllers\Admin\PublicSections;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Models\PublicSections\Fqs;
use App\Http\Requests\Admin\PublicSections\Fqs\StoreRequest;
use App\Services\PublicSections\FqsService;

class FqsController extends AdminBasicController
{
    public function __construct(){
        $this->model = Fqs::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = StoreRequest::class;
        $this->directoryName = 'public-sections.fqs';
        $this->serviceName = new FqsService();
        $this->indexScopes = 'search';
    }

}
