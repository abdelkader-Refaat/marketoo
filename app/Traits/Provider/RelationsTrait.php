<?php

namespace App\Traits\Provider;

use App\Models\Order;
use App\Models\Course;
use App\Models\Category;
use App\Models\Ability;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait RelationsTrait
{
    // Relations
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
