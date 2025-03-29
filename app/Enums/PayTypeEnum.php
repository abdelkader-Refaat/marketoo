<?php

namespace App\Enums;


enum PayTypeEnum: int
{
    case UNDEFINED = 0;
    case CASH = 1;
    case WALLET = 2;
    case APPLE_PAY = 3;
    case ONLINE = 4;
}
