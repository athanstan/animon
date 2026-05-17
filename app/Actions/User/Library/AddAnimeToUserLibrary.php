<?php

declare(strict_types=1);

namespace App\Actions\User\Library;

use App\Enums\UserAnimeLibraryStatus;
use App\Events\User\Library\AnimeAddedToUserLibrary;
use App\Exceptions\User\Library\AnimeAlreadyInUserLibraryException;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserAnimeLibrary;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Event;

final class AddAnimeToUserLibrary
{
    public function execute(
        User $user,
        Anime $anime,
        UserAnimeLibraryStatus $status,
    ): UserAnimeLibrary {
        try {
            $library = UserAnimeLibrary::query()->create([
                'user_id' => $user->id,
                'anime_id' => $anime->id,
                'status' => $status,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new AnimeAlreadyInUserLibraryException;
        }

        Event::dispatch(new AnimeAddedToUserLibrary(
            $user,
            $anime,
            $library,
            wasRecentlyCreated: true,
        ));

        return $library;
    }
}
