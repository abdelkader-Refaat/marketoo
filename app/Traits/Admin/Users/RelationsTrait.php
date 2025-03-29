<?php

namespace App\Traits\Admin\Users;

use App\Models\City;
use App\Models\Country;



trait RelationsTrait
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
