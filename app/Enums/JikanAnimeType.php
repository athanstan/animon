<?php

declare(strict_types=1);

namespace App\Enums;

enum JikanAnimeType: string
{
    case TV = 'tv';
    case Movie = 'movie';
    case OVA = 'ova';
    case Special = 'special';
    case ONA = 'ona';
    case Music = 'music';
}
