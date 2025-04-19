<?php

namespace App\Traits\Provider;

use App\Models\Ability;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait RelationsTrait
{
    // Relations
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_provider', 'provider_id', 'category_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }
}
