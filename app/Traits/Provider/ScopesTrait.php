<?php

namespace App\Traits\Provider;

trait ScopesTrait
{
    public function scopeValid($query)
    {
        return $query->where([
            ['is_approved', true],
            ['active', true],
            ['is_blocked', false]
        ]);
    }
}
