<?php

namespace app\Http\Controllers\Api\V1\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\General\CountriesCities\CityResource;
use App\Http\Resources\Api\General\CountriesCities\CountryResource;
use App\Services\CountryCities\CityService;
use App\Services\CountryCities\CountryService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class CountriesCitiesController extends Controller
{
    use ResponseTrait;

    public function __construct(protected CityService $cityService, protected CountryService $countryService)
    {
    }

    public function getCountries(): JsonResponse
    {
        $data = $this->countryService->all();
        return $this->jsonResponse(data: CountryResource::collection($data));
    }

    public function getCountryCities($country_id): JsonResponse
    {
        $data = $this->cityService->all(conditions: ['country_id' => $country_id], with: ['country.cities']);
        return $this->jsonResponse(data: CityResource::collection($data));
    }
}
