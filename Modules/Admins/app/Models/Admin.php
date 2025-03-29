<?php

namespace Modules\Admins\app\Models;
use App\Models\Core\AuthBaseModel;
use App\Models\PublicSettings\Role;

class Admin extends AuthBaseModel {

    const IMAGEPATH = 'admins';

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_notify'  => 'boolean',
            'is_blocked' => 'boolean',        ];
    }

    public function role() {
        return $this->belongsTo(Role::class)->withTrashed();
    }
}
