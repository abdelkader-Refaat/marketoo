<?php

namespace App\Models\Core;

use App\Models\Core\BaseModel;

class AuthUpdate extends BaseModel
{
    protected $fillable = ['type', 'attribute', 'code', 'country_code', 'updatable_id', 'updatable_type'];

    private function activationCode(): int
    {
        return 123456;
//        return mt_rand(111111, 999999);
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = !is_null($value) ? $this->activationCode() : null;
    }
}
