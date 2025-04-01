<?php

namespace App\Models\Wallet;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends BaseModel
{

    protected $fillable = [
        'walletable_id',
        'walletable_type',
        'balance',
        'available_balance',
        'debt_balance',
    ];

    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

}
