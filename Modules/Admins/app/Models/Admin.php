<?php

namespace Modules\Admins\App\Models;

use App\Models\Core\AuthBaseModel;
use App\Models\PublicSettings\Role;

class Admin extends AuthBaseModel
{

    const IMAGEPATH = 'admins';
    const FILEPATH = 'admins';
    const FILES = ['avatar'];

    protected $fillable = [
        'name',
        'phone',
        'country_code',
        'email',
        'password',
        'image',
        'role_id',
        'is_notify',
        'is_blocked',
        'type',
    ];

    protected static function newFactory()
    {
        return \Modules\Admins\Database\Factories\AdminFactory::new();
    }

    public function role()
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }

    protected function casts(): array
    {
        return [
            'is_notify' => 'boolean',
            'is_blocked' => 'boolean',
            'password' => 'hashed',
        ];
    }


}
