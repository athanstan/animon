<?php

declare(strict_types=1);

namespace App\Enums;

enum TopAnimeFilter: string
{
    case Airing = 'airing';
    case Upcoming = 'upcoming';
    case ByPopularity = 'bypopularity';
    case Favorite = 'favorite';
}
