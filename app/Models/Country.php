<?php

namespace App\Models;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class Country extends BaseModel
{
    use HasTranslations;
    const IMAGEPATH = 'countries';

    protected $fillable = ['name', 'key', 'flag'];

    public $translatable = ['name'];

    public function getFlagAttribute(): string
    {
        if ($this->attributes['flag']) {
            $flag = asset('admin/assets/flags/png/' . $this->attributes['flag']);
        } else {
            $flag = $this->defaultImage('countries');
        }
        return $flag;
    }

    public function myFlag()
    {
        return $this->attributes['flag'];
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
