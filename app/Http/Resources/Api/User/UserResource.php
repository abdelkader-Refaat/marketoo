<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Basics\BasicResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private ?string $token = null;

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'country_flag' => optional($this->country)->flag,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'full_phone' => $this->full_phone,
            'lang' => $this->lang,
            'is_notify' => $this->is_notify,
            'country' => BasicResource::make($this->whenLoaded('country')),
            'city' => BasicResource::make($this->whenLoaded('city')),
            'token' => $this->when($this->token, $this->token),
        ];
    }
}
