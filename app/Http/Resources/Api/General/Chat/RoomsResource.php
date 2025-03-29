<?php

namespace App\Http\Resources\Api\General\Chat;

use App\Services\Chat\ChatService;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomsResource extends JsonResource
{    
    public function toArray($request)
    {
        $chat = new ChatService();
        $members = $chat->getOtherRoomMembers($this, auth()->user());

        return [
            'id'                        => $this->id,
            'members'                   => MembersResource::collection($members),
            'last_message_body'         => $this->lastOriginalMessage->body??'',
            'last_message_created_at'   => $this->lastOriginalMessage->created_at->diffForHumans()
        ];
    }
}