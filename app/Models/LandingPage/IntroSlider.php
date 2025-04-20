<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class IntroSlider extends BaseModel
{
    use HasTranslations;

    const string IMAGEPATH = 'intro_sliders';
    public array $translatable = ['title', 'description'];
    protected $fillable = ['image', 'title', 'description'];
}
