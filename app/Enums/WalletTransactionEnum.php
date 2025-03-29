<?php

namespace App\Enums;

/**
 * Class OrderType
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
*/
class WalletTransactionEnum extends Base
{
    public const CHARGE     = 0;
    public const DEBT       = 1;
    public const PAY_ORDER   = 2;
}
