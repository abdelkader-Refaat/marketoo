<?php

namespace App\Services\Payment;

use App\Models\Wallet\Wallet;

use App\Traits\ResponseTrait;
use App\Enums\WalletTransactionEnum;
use Modules\Admins\App\Models\Admin;

class PayProcess
{
    use ResponseTrait;

    public function payAmountToAdmin($amount)
    {
        $toWallet = Wallet::firstOrCreate(['walletable_type' => Admin::class, 'walletable_id' => 1]);

        $toWallet->increment('balance', $amount);

        $toWallet->transactions()->create([
            'amount' => $amount,
            'type' => WalletTransactionEnum::CHARGE,
        ]);

        return true;
    }

    public function cutAmountFromAdminForSettlement($amount)
    {
        $fromWallet = Wallet::firstOrCreate(['walletable_type' => Admin::class, 'walletable_id' => 1]);

        $fromWallet->decrement('balance', $amount);

        $fromWallet->walletHistory()->create([
            'amount' => $amount,
            'type' => WalletTransactionEnum::DEBT,
        ]);

        return true;
    }
}
