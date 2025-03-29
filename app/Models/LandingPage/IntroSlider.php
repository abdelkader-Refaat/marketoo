<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class IntroSlider extends BaseModel
{
    use HasTranslations;
    const IMAGEPATH = 'intro_sliders' ;
    protected $fillable = ['image','title','description'];
    public $translatable = ['title' , 'description'];
}
