<?php

namespace App\Models\LandingPage;

use App\Models\Core\BaseModel;

class IntroPartner extends BaseModel
{
    protected $fillable = ['image'];
    const IMAGEPATH = 'partners' ;
}
