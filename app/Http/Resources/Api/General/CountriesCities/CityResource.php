<?php

namespace App\Http\Resources\Api\General\CountriesCities;

use App\Http\Resources\Api\Basics\BasicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this?->id,
            'name'    => $this?->name,
            'country' => BasicResource::make($this?->country),
        ];
    }
}
