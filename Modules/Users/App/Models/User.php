<?php

namespace Modules\Users\App\Models;

use App\Models\Core\AuthBaseModel;
use App\Models\PublicSettings\Role;
use App\Traits\Admin\Users\RelationsTrait;

class User extends AuthBaseModel
{
    use RelationsTrait;

    const IMAGEPATH = 'users';
    const FILES = ['avatar'];
    const FILEPATH = 'users';

    protected $fillable = [
        'name',
        'avatar',
        'country_code',
        'phone',
        'email',
        'password',
        'city_id',
        'country_id',
        'active',
        'is_blocked',
        'is_approved',
        'lang',
        'is_notify',
        'code',
        'type',
        'code_expire',
    ];


    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'active' => 'boolean',
            'is_notify' => 'boolean',
            'type' => 'integer',
            'password' => 'hashed'

        ];
    }

}
