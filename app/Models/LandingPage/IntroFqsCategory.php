<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class IntroFqsCategory extends BaseModel
{
    use HasTranslations;
    protected $fillable = ['title'];
    public $translatable = ['title'];
    public function questions()
    {
        return $this->hasMany(IntroFqs::class, 'intro_fqs_category_id', 'id');
    }
}
