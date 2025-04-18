<?php

namespace App\Http\Resources\Api\General\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'image' => $this->image,
        ];
    }
}
