<?php

namespace Modules\Providers\App\Models;


use App\Models\Core\AuthBaseModel;
use App\Traits\Provider\RelationsTrait;


class Provider extends AuthBaseModel
{
    const string  IMAGEPATH = 'providers';

    const array FILES = ['avatar'];

    const string FILEPATH = 'providers';

    use RelationsTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'avatar',
        'cover',
        'country_code',
        'phone',
        'email',
        'password',
        'city_id',
        'country_id',
        'is_active',
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
            'is_active' => 'boolean',
            'is_notify' => 'boolean',
            'type' => 'integer',
            'password' => 'hashed',

        ];
    }
}
