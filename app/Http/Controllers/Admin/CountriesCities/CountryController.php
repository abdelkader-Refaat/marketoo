<?php

namespace App\Http\Controllers\Admin\CountriesCities;

use App\Models\Country;
use App\Services\CountryCities\CountryService;
use App\Http\Requests\Admin\CountriesCities\Country\StoreRequest;
use App\Http\Requests\Admin\CountriesCities\Country\UpdateRequest;
use App\Http\Controllers\Admin\Core\AdminBasicController;

class CountryController extends AdminBasicController
{
    public function __construct() {

        $this->model = Country::class;
        $this->storeRequest  = StoreRequest::class;
        $this->updateRequest  = UpdateRequest::class;
        $this->directoryName = 'countries';
        $this->serviceName = new CountryService();
        $this->indexScopes = 'search';
        $this->createCompactVariables = ['flags' => $this->serviceName->getFlags()];
        $this->editCompactVariables = ['flags' => $this->serviceName->getFlags()];
        $this->indexConditions = [];
    }

}
