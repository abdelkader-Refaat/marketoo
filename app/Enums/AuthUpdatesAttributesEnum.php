<?php

namespace App\Enums;

enum AuthUpdatesAttributesEnum: int
{
    case Phone = 1;
    case NewPhone = 2;
    case Email = 3;
    case NewEmail = 4;
    case Password = 5;
}