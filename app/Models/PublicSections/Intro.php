<?php

namespace App\Models\PublicSections;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class Intro extends BaseModel
{
    const IMAGEPATH = 'intros' ;
    use HasTranslations;
    protected $fillable = ['title','description' ,'image'];
    public $translatable = ['title','description'];

}
