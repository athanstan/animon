<?php

declare(strict_types=1);

namespace App\Enums;

enum EpisodeStatus: string
{
    case Watched = 'watched';
    case Skipped = 'skipped';
}
