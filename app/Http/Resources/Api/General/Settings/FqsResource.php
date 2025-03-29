<?php

namespace App\Http\Resources\Api\General\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class FqsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'question' => $this->question,
            'answer'   => $this->answer,
        ];
    }
}
