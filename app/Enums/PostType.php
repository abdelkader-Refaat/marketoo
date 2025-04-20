<?php

namespace App\Enums;

enum PostType: int
{
    case  TEXT           = 0;
    case  IMAGE          = 1;
    case  VIDEO          = 2;
    case  CELEBRATION    = 3;
    case EVENT          = 4;
    case  DOCUMENT       = 5;
}
