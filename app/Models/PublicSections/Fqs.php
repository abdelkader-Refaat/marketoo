<?php

namespace App\Models\PublicSections;

use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;

class Fqs extends BaseModel
{
    use HasTranslations;
    protected $fillable = ['question','answer'];
    public $translatable = ['question','answer'];

}
