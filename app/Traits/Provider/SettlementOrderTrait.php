<?php

namespace App\Traits;

use App\Enums\SettlementStatusEnum;

trait SettlementOrderTrait
{
  public function settlementStatuses($settlementStatus)
  {
    return match ($settlementStatus) {
      'current' => [SettlementStatusEnum::PENDING->value],
      'completed' => [SettlementStatusEnum::ACCEPTED->value, SettlementStatusEnum::REJECTED->value],
      default => [],
    };
  }
}
