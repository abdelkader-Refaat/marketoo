<?php

namespace App\Http\Resources\Api\General\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
        ];
    }
}
