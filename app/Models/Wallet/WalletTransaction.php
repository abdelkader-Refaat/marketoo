<?php

namespace App\Models\Wallet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'transactionable_id',
        'transactionable_type',
    ];

    public function getTypeTextAttribute($value)
    {
        return  __('admin.wallet_type_'.$this->attributes['type']) ;
    }



    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }



    public function transactionable()
    {
        return $this->morphTo();
    }
}
