<?php

namespace App\Models\Core;

use App\Models\Core\BaseModel;

class SMS extends BaseModel
{
    protected $fillable = ['name','active','sender_name','key' ,'user_name' , 'password'];
}
