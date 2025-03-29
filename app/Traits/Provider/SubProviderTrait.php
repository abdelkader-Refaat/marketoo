<?php

namespace App\Traits\Provider;

trait SubProviderTrait
{
    public function getParentId()
    {
        return $this->parent_id ?? $this->id;
    }
}
