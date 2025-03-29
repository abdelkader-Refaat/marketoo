<?php

namespace App\Http\Resources\Api\Basics;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResourceWithImage extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
        ];

    }
}
