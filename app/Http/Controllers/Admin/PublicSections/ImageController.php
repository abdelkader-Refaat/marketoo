<?php

namespace App\Http\Controllers\Admin\PublicSections;

use App\Models\PublicSections\Image;
use App\Services\PublicSections\ImageService;
use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Http\Requests\Admin\PublicSections\Images\StoreRequest;
use App\Http\Requests\Admin\PublicSections\Images\UpdateRequest;


class ImageController extends AdminBasicController
{
    public function __construct()
    {
        $this->model = Image::class;
        $this->storeRequest = StoreRequest::class;
        $this->updateRequest = UpdateRequest::class;
        $this->directoryName = 'public-sections.images';
        $this->serviceName = new ImageService();
        $this->indexScopes = 'search';
    }
}
