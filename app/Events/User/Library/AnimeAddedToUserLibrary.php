<?php

declare(strict_types=1);

namespace App\Events\User\Library;

use App\Events\User\UserEvent;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserAnimeLibrary;

final class AnimeAddedToUserLibrary extends UserEvent
{
    public function __construct(
        User $user,
        public Anime $anime,
        public UserAnimeLibrary $libraryEntry,
        public bool $wasRecentlyCreated,
    ) {
        parent::__construct($user);
    }
}
