<?php

namespace App\Http\Resources\Api\User;

use App\Http\Resources\Api\Basics\BasicResource;
use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $token = '';

    public function setToken($value)
    {
        $this->token = $value;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'id' => $this?->id,
            'name' => $this?->name,
            'email' => $this?->email,
            'country_code' => $this?->country_code,
            'country_flag' => Country::where('key', 'like', '%'.$this->country_code.'%')->first()?->flag,
            'phone' => $this?->phone,
            'avatar' => $this?->avatar,
            'full_phone' => $this?->full_phone,
            'image' => $this?->image,
            'lang' => $this?->lang,
            'is_notify' => $this?->is_notify,
            'country' => BasicResource::make($this->country),
            'city' => BasicResource::make($this->city),
            'token' => $this->when($this->token, $this->token),
        ];
    }
}
