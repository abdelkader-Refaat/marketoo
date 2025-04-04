<?php

namespace App\Models;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Users\App\Models\User;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name', 'country_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
