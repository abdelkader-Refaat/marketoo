<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class IntroService extends BaseModel
{
    use HasTranslations;
    protected $fillable = ['title' , 'description'];
    public $translatable = ['title' , 'description'];
}
