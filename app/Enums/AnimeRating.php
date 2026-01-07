<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeRating: string
{
    case G = 'G - All Ages';
    case PG = 'PG - Children';
    case PG13 = 'PG-13 - Teens 13 or older';
    case R17 = 'R - 17+ (violence & profanity)';
    case R = 'R+ - Mild Nudity';
    case Rx = 'Rx - Hentai';
}
