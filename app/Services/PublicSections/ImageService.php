<?php

namespace App\Services\PublicSections;

use App\Services\Core\BaseService;
use App\Models\PublicSections\Image;

class ImageService extends BaseService
{
    public function __construct()
    {
        $this->model = Image::class;
    }

}
