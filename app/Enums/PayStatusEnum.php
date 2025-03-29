<?php

namespace App\Enums;


enum PayStatusEnum : int
{
    case PENDING     = 0;
    case PAID        = 1;
    case UNPAID      = 2;
}
