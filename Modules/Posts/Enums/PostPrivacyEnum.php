<?php

namespace Modules\Posts\Enums;

enum PostPrivacyEnum: int
{
    case Public = 1;
    case Private = 2;
    case Unlisted = 3;
}
