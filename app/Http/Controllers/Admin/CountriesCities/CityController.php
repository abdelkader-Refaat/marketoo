<?php

namespace App\Http\Controllers\Admin\CountriesCities;

use App\Models\City;
use Illuminate\Http\Request;
use App\Services\CountryCities\CityService;
use App\Services\CountryCities\CountryService;
use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Http\Requests\Admin\CountriesCities\City\StoreRequest;
use App\Http\Requests\Admin\CountriesCities\City\UpdateRequest;

class CityController extends AdminBasicController
{
    public function __construct(protected CountryService $countryService)
    {

        $this->model = City::class;
        $this->storeRequest  = StoreRequest::class;
        $this->updateRequest  = UpdateRequest::class;
        $this->directoryName = 'cities';
        $this->serviceName = new CityService();
        $this->indexScopes = 'search';
        $this->indexCompactVariables = ['countries' => $countryService->all()];
        $this->createCompactVariables = ['countries' => $countryService->all()];
        $this->editCompactVariables = ['countries' => $countryService->all()];
    }

    public function getCities(Request $request)
    {
        $cities = $this->serviceName->all(conditions: ['country_id' => $request->country_id]);
        return response()->json(['key' => 'success', 'cities' => $cities]);
    }
}
