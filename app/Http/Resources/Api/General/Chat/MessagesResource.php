<?php

namespace App\Http\Resources\Api\General\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class MessagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'sender_id'  => $this->originalMessage?->senderable?->id,
            'avatar'     => $this->originalMessage?->senderable?->image,
            'is_sender'  => $this->is_sender,
            'body'       => $this->originalMessage?->body ?? '',
            'type'       => $this->originalMessage?->type ?? '',
            'duration'   => $this->originalMessage?->duration ?? '0.0',
            'name'       => $this->originalMessage?->name ?? '',
            'created_at' => $this->originalMessage?->created_at?->diffForHumans()
        ];
    }
}