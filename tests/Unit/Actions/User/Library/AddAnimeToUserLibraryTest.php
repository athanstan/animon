<?php

declare(strict_types=1);

use App\Actions\User\Library\AddAnimeToUserLibrary;
use App\Enums\UserAnimeLibraryStatus;
use App\Events\User\Library\AnimeAddedToUserLibrary;
use App\Exceptions\User\Library\AnimeAlreadyInUserLibraryException;
use App\Models\Anime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a library entry, sets status, and dispatches event as new', function () {
    Event::fake();

    $user = User::factory()->create();
    $anime = Anime::factory()->create();

    $entry = app(AddAnimeToUserLibrary::class)->execute(
        $user,
        $anime,
        UserAnimeLibraryStatus::Watching,
    );

    expect($entry->user_id)->toBe($user->id)
        ->and($entry->anime_id)->toBe($anime->id)
        ->and($entry->status)->toBe(UserAnimeLibraryStatus::Watching);

    Event::assertDispatched(AnimeAddedToUserLibrary::class, function (AnimeAddedToUserLibrary $event) use ($user, $anime, $entry): bool {
        return $event->user->is($user)
            && $event->anime->is($anime)
            && $event->libraryEntry->is($entry)
            && $event->wasRecentlyCreated === true;
    });
});

it('throws when the anime is already in the library due to the unique constraint', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create();

    app(AddAnimeToUserLibrary::class)->execute($user, $anime, UserAnimeLibraryStatus::Watching);

    Event::fake([AnimeAddedToUserLibrary::class]);

    expect(fn () => app(AddAnimeToUserLibrary::class)->execute(
        $user,
        $anime,
        UserAnimeLibraryStatus::PlanToWatch,
    ))->toThrow(AnimeAlreadyInUserLibraryException::class);

    Event::assertNotDispatched(AnimeAddedToUserLibrary::class);
});
