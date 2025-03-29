<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class IntroHowWork extends BaseModel
{
    use HasTranslations;
    const IMAGEPATH = 'how_works' ;
    protected $fillable = ['title','image'];
    public $translatable = ['title'];

}
