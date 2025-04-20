<?php

namespace App\Http\Resources\Api\User;

use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    use PaginationTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'pagination' => $this->paginationModel($this),
                'users' => UserResource::collection($this->collection),
            ];
    }
}
