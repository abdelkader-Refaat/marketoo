<?php

namespace Modules\Posts\Transformers;

use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{
    use PaginationTrait;
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return
            [
                'pagination' => $this->paginationModel($this),
                'posts' => PostResource::collection($this->collection),
            ];


    }
}
