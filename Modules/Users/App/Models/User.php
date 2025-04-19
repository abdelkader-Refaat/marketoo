<?php

namespace Modules\Users\App\Models;

use App\Models\City;
use App\Models\Core\AuthBaseModel;
use App\Models\Country;
use App\Models\PaymentTransaction;
use App\Traits\Admin\Users\RelationsTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Posts\App\Models\Post;

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
        'is_active',
        'is_blocked',
        'is_approved',
        'lang',
        'is_notify',
        'code',
        'type',
        'code_expire',
    ];

    public function paymentTransactions(): MorphMany
    {
        return $this->morphMany(PaymentTransaction::class, 'payer');
    }

    public function findForPassport($identifier)
    {
        return self::where('phone', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

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
