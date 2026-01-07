<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeStatus: string
{
    case Airing = 'Currently Airing';
    case Complete = 'Finished Airing';
    case Upcoming = 'Not yet aired';
}
