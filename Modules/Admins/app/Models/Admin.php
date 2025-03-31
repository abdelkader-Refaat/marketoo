<?php

namespace Modules\Admins\App\Models;

use App\Models\Core\AuthBaseModel;
use App\Models\PublicSettings\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class Admin extends AuthBaseModel implements FilamentUser
{

    const IMAGEPATH = 'admins';
    const FILEPATH = 'admins';
    const FILES = ['avatar'];

    protected $fillable = [
        'name',
        'avatar',
        'phone',
        'type',
        'country_code',
        'email',
        'password',
        'role_id',
        'is_blocked',
        'is_notify',
    ];

    protected static function newFactory()
    {
        return \Modules\Admins\Database\Factories\AdminFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
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
