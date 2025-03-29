<?php

namespace App\Http\Resources\Api\General\Chat;

use App\Traits\PaginationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessagesCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request)
    {
        return [
            'pagination' => $this->paginationModel($this),
            'data'       => MessagesResource::collection($this->collection),
        ];

    }
}
