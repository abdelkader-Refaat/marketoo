<?php

namespace App\Models\Wallet;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends BaseModel
{

    protected $fillable = [
        'wallet_id',
        'transactionable_id',
        'transactionable_type',
        'pay_type',
        'amount',
    ];

    public function getTypeTextAttribute($value): string
    {
        return __('admin.wallet_type_'.$this->attributes['pay_type']);
    }


    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }


    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
