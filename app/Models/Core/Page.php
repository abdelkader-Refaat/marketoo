<?php

namespace App\Models\Core;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class Page extends BaseModel
{
    use HasTranslations;
    protected $fillable = ['title', 'slug', 'content'];
    public $translatable = ['title', 'content'];
}
