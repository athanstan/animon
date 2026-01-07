<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeType: string
{
    case TV = 'TV';
    case OVA = 'OVA';
    case Movie = 'Movie';
    case Special = 'Special';
    case ONA = 'ONA';
    case Music = 'Music';
    case CM = 'CM';
    case PV = 'PV';
    case TVSpecial = 'TV Special';
}
