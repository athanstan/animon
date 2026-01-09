<?php

declare(strict_types=1);

namespace App\Enums;

enum JikanRating: string
{
    case G = 'g';
    case PG = 'pg';
    case PG13 = 'pg13';
    case R17 = 'r17';
    case R = 'r';
    case Rx = 'rx';
}
