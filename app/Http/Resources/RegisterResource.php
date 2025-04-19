<?php

namespace App\Http\Resources;

use App\Enums\UserTypesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    public function __construct($resource, protected string $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'country_code' => $this->country_code,
            'phone' => $this->phone ?? null,
            'full_phone' => $this->full_phone,
            'email' => $this->email,
            //            'category' => $this->category?->name ?? "",
            'country' => $this->country?->name,
            'city' => $this->city?->name,
            'is_active' => (bool) $this->active,
            'is_blocked' => (bool) $this->is_blocked,
//            'is_approved' => (bool) $this->is_approved,
            'type' => strtolower(UserTypesEnum::tryFrom($this->type)?->name),
            'token' => $this->token,
            //            'type' => $this->
        ];
    }
}
