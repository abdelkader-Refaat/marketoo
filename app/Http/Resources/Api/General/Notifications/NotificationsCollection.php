<?php

namespace App\Http\Resources\Api\General\Notifications;

use App\Traits\PaginationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationsCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request)
    {
        return [
            'pagination' => $this->paginationModel($this),
            'data'       => NotificationsResource::collection($this->collection),
        ];

    }
}
