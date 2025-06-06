<?php

namespace App\Http\Resources\Api\General\CountriesCities;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'name' => $this?->name,
            'key' => $this?->key,
            'flag' => $this?->flag,
            'cities' => $this->whenLoaded('cities', CityResource::collection($this->cities)),
        ];
    }
}
