<?php

namespace App\Http\Resources\Api\Basics;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'name' => $this?->name,
        ];
    }
}
