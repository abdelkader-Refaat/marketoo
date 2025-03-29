<?php

namespace App\Services\CountryCities;

use App\Models\City;
use App\Services\Core\BaseService;

class CityService extends BaseService
{
    public function __construct()
    {
        $this->model = City::class;
    }
}
