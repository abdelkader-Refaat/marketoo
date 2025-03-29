<?php

namespace App\Enums;


enum UserTypesEnum: int
{
    case INDIVIDUAL  = 1;
    case ACTION      = 2;
    case Company      = 3;
}
