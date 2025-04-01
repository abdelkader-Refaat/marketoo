<?php

namespace App\Enums;

enum MyFatoorahPaymentMethod: int
{
    case KNET = 2;
    case VISA_MASTERCARD = 3;
    case APPLE_PAY = 6;
    case MEEZA = 7;
    case BENEFIT = 8;
}
