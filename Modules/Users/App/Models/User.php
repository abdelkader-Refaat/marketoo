<?php

namespace Modules\Users\App\Models;
use App\Models\Core\AuthBaseModel;
use App\Models\PublicSettings\Role;
use App\Traits\Admin\Users\RelationsTrait;

class User extends AuthBaseModel
{
    use RelationsTrait;
    const IMAGEPATH = 'users';

    protected $fillable = [
        'name',
        'country_code',
        'phone',
        'email',
        'image',
        'password',
        'city_id',
        'country_id',
        'active',
        'is_blocked',
        'is_approved',
        'lang',
        'is_notify',
        'code',
        'code_expire',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_blocked'  => 'boolean',
            'active'      => 'boolean',
            'is_notify'   => 'boolean',
            ];
    }

}
