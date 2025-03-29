<?php

namespace App\Traits;

use App\Enums\OrderPayStatus;
use App\Enums\OrderPayType;
use App\Services\User\WalletService;

trait PaymentServicesTrait
{
    public function payWithWallet($user, $order): array
    {
        if ($user->wallet->available_balance < $order->final_total) {
            return ['key' => 'fail', 'msg' => __('apis.not_enough_money'), 'data' => []];
        }
        $res = (new WalletService())->debt($user->wallet, $order->final_total);
        if ($res['key'] == 'success') {
            $order->update([
                'pay_type'   => OrderPayType::WALLET->value,
                'pay_status' => OrderPayStatus::PAID->value,
            ]);
            return ['key' => $res['key'], 'msg' => $res['msg'], 'data' => []];
        }
        return $res;
    }

    public function payWithCash($order): array
    {
        $order->update([
            'pay_type' => OrderPayType::CASH->value,
        ]);
        return ['key' => 'success', 'msg' => __('apis.selected_successfully'), 'data' => []];
    }

    public function payWithOnline($order, $request): array
    {
        $order->update([
            'pay_type'   => OrderPayType::ONLINE->value,
            'pay_status' => OrderPayStatus::PAID->value,
        ]);
        return ['key' => 'success', 'msg' => __('apis.success'), 'data' => []];
    }

    public function payWithApplePay($order, $request): array
    {
        $order->update([
            'pay_type'   => OrderPayType::ONLINE->value,
            'pay_status' => OrderPayStatus::PAID->value,
        ]);
        return ['key' => 'success', 'msg' => __('apis.success'), 'data' => []];
    }
}
