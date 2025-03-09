<?php

namespace Modules\Posts\Transformers;

use Illuminate\Http\Request;
use Modules\Posts\Helpers\PostHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'privacy' => $this->privacy,
            'is_promoted' => (bool) $this->is_promoted,
            'event_details' => [
                'name' => $this->event_name,
                'date_time' => $this->event_date_time,
                'description' => $this->event_description,
            ],
            'repost' => $this->repost_id ? [
                'id' => $this->repost_id,
                'text' => $this->repost_text,
            ] : null,
            'deleted_at' => $this->deleted_at,
            'timestamps' => [
                'created_at' => timeAgo($this->created_at),
                'updated_at' => timeAgo($this->updated_at),
            ]
        ];

    }
}
