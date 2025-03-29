<?php

namespace App\Http\Controllers\Admin\PublicSettings;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Traits\ReportTrait;
use Illuminate\Http\Request;
use App\Models\PublicSettings\Social;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\PublicSettings\Socials\StoreRequest;
use App\Services\PublicSettings\SocialService;

class SocialController extends AdminBasicController
{

    public function __construct()
    {
        $this->model = Social::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = StoreRequest::class;
        $this->directoryName = 'public-settings.socials';
        $this->serviceName = new SocialService();
        $this->indexScopes = 'search';
    }

    // social has observer to Cache::forget('socials'); when created, updated, deleted
}
