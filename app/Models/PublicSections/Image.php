<?php

namespace App\Models\PublicSections;

use App\Models\Core\BaseModel;


class Image extends BaseModel
{
    const IMAGEPATH = 'images' ;
    protected $fillable = ['image'];
}
