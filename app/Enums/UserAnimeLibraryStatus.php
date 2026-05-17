<?php

declare(strict_types=1);

namespace App\Enums;

enum UserAnimeLibraryStatus: string
{
    case Watching = 'watching';
    case Completed = 'completed';
    case Dropped = 'dropped';
    case PlanToWatch = 'plan_to_watch';
}
