<?php

namespace App\Models\PublicSettings;

use App\Models\Core\BaseModel;

class SiteSetting extends BaseModel
{
    protected $fillable = ['key', 'value'];
}
