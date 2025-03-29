<?php

namespace App\Models;

use App\Models\AllUsers\User;
use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use HasTranslations;

    protected $fillable = ['name', 'country_id'];

    public $translatable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

}
