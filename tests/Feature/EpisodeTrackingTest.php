<?php

use App\Actions\SyncEpisodesFromJikan;
use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\EpisodeDTO;
use App\Enums\EpisodeStatus;
use App\Interfaces\JikanInterface;
use App\Livewire\Anime\Episodes;
use App\Models\Anime;
use App\Models\Episode;
use App\Models\EpisodeUser;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// --- Episode Model & Factory Tests ---

it('creates an episode with factory', function () {
    $episode = Episode::factory()->create();

    expect($episode)->toBeInstanceOf(Episode::class)
        ->and($episode->anime)->toBeInstanceOf(Anime::class)
        ->and($episode->number)->toBeInt()
        ->and($episode->title)->toBeString();
});

it('belongs to an anime', function () {
    $anime = Anime::factory()->create();
    $episode = Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    expect($episode->anime->id)->toBe($anime->id);
});

it('enforces unique constraint on anime_id and number', function () {
    $anime = Anime::factory()->create();
    Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);
})->throws(QueryException::class);

it('allows same number for different anime', function () {
    $anime1 = Anime::factory()->create();
    $anime2 = Anime::factory()->create();

    $ep1 = Episode::factory()->create(['anime_id' => $anime1->id, 'number' => 1]);
    $ep2 = Episode::factory()->create(['anime_id' => $anime2->id, 'number' => 1]);

    expect($ep1->id)->not->toBe($ep2->id);
});

// --- SyncEpisodesFromJikan Action Tests ---

it('syncs episodes from EpisodeCollection to database', function () {
    $anime = Anime::factory()->create(['mal_id' => 1]);

    $episodes = new EpisodeCollection([
        new EpisodeDTO(
            malId: 1, url: 'https://example.com', title: 'Episode 1',
            titleJapanese: null, titleRomanji: null, aired: now()->subDay(),
            score: 4.5, filler: false, recap: false, forumUrl: null,
        ),
        new EpisodeDTO(
            malId: 2, url: 'https://example.com', title: 'Episode 2',
            titleJapanese: null, titleRomanji: null, aired: now(),
            score: 3.8, filler: true, recap: false, forumUrl: null,
        ),
    ]);

    app(SyncEpisodesFromJikan::class)->execute($anime->id, $episodes);

    expect(Episode::where('anime_id', $anime->id)->count())->toBe(2)
        ->and(Episode::where('anime_id', $anime->id)->where('number', 1)->first()->title)->toBe('Episode 1')
        ->and(Episode::where('anime_id', $anime->id)->where('number', 2)->first()->filler)->toBeTrue();
});

it('upserts episodes without creating duplicates', function () {
    $anime = Anime::factory()->create(['mal_id' => 1]);

    $episodes = new EpisodeCollection([
        new EpisodeDTO(
            malId: 1, url: null, title: 'Original Title',
            titleJapanese: null, titleRomanji: null, aired: null,
            score: null, filler: false, recap: false, forumUrl: null,
        ),
    ]);

    $action = app(SyncEpisodesFromJikan::class);
    $action->execute($anime->id, $episodes);
    $action->execute($anime->id, $episodes);

    expect(Episode::where('anime_id', $anime->id)->count())->toBe(1);
});

it('updates existing episode data on sync', function () {
    $anime = Anime::factory()->create(['mal_id' => 1]);

    $original = new EpisodeCollection([
        new EpisodeDTO(
            malId: 1, url: null, title: 'Original',
            titleJapanese: null, titleRomanji: null, aired: null,
            score: null, filler: false, recap: false, forumUrl: null,
        ),
    ]);

    $updated = new EpisodeCollection([
        new EpisodeDTO(
            malId: 1, url: null, title: 'Updated Title',
            titleJapanese: null, titleRomanji: null, aired: null,
            score: 4.0, filler: true, recap: false, forumUrl: null,
        ),
    ]);

    $action = app(SyncEpisodesFromJikan::class);
    $action->execute($anime->id, $original);
    $action->execute($anime->id, $updated);

    $episode = Episode::where('anime_id', $anime->id)->where('number', 1)->first();
    expect($episode->title)->toBe('Updated Title')
        ->and($episode->filler)->toBeTrue();
});

it('does nothing with empty episode collection', function () {
    $anime = Anime::factory()->create(['mal_id' => 1]);

    app(SyncEpisodesFromJikan::class)->execute($anime->id, new EpisodeCollection);

    expect(Episode::count())->toBe(0);
});

// --- Episode Tracking (Pivot) Tests ---

it('allows a user to mark an episode as watched', function () {
    $user = User::factory()->create();
    $episode = Episode::factory()->create();

    $episode->users()->attach($user->id, ['status' => EpisodeStatus::Watched->value]);

    expect(EpisodeUser::where('user_id', $user->id)->where('episode_id', $episode->id)->first())
        ->status->toBe(EpisodeStatus::Watched);
});

it('allows a user to mark an episode as skipped', function () {
    $user = User::factory()->create();
    $episode = Episode::factory()->create();

    $episode->users()->attach($user->id, ['status' => EpisodeStatus::Skipped->value]);

    expect(EpisodeUser::where('user_id', $user->id)->where('episode_id', $episode->id)->first())
        ->status->toBe(EpisodeStatus::Skipped);
});

it('enforces unique constraint on episode_id and user_id', function () {
    $user = User::factory()->create();
    $episode = Episode::factory()->create();

    EpisodeUser::create(['user_id' => $user->id, 'episode_id' => $episode->id, 'status' => 'watched']);
    EpisodeUser::create(['user_id' => $user->id, 'episode_id' => $episode->id, 'status' => 'skipped']);
})->throws(QueryException::class);

// --- Livewire Component Tests ---

it('toggles episode status to watched for authenticated user', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create(['mal_id' => 100]);
    $episode = Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    $jikanMock = Mockery::mock(JikanInterface::class);
    $jikanMock->shouldReceive('getAnimeEpisodesPagination')->andReturn([
        'last_visible_page' => 1,
        'has_next_page' => false,
    ]);
    $jikanMock->shouldReceive('getAnimeEpisodes')->andReturn(new EpisodeCollection);
    app()->instance(JikanInterface::class, $jikanMock);

    $this->actingAs($user);

    Livewire::test(Episodes::class, ['animeId' => $anime->id, 'malId' => 100])
        ->call('toggleEpisodeStatus', 1, 'watched');

    expect(EpisodeUser::where('user_id', $user->id)->where('episode_id', $episode->id)->first())
        ->status->toBe(EpisodeStatus::Watched);
});

it('toggles off episode status when same status is clicked again', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create(['mal_id' => 101]);
    $episode = Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    $jikanMock = Mockery::mock(JikanInterface::class);
    $jikanMock->shouldReceive('getAnimeEpisodesPagination')->andReturn([
        'last_visible_page' => 1,
        'has_next_page' => false,
    ]);
    $jikanMock->shouldReceive('getAnimeEpisodes')->andReturn(new EpisodeCollection);
    app()->instance(JikanInterface::class, $jikanMock);

    $this->actingAs($user);

    Livewire::test(Episodes::class, ['animeId' => $anime->id, 'malId' => 101])
        ->call('toggleEpisodeStatus', 1, 'watched')
        ->call('toggleEpisodeStatus', 1, 'watched');

    expect(EpisodeUser::where('user_id', $user->id)->where('episode_id', $episode->id)->first())
        ->toBeNull();
});

it('changes episode status when toggled with different status', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create(['mal_id' => 102]);
    $episode = Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    $jikanMock = Mockery::mock(JikanInterface::class);
    $jikanMock->shouldReceive('getAnimeEpisodesPagination')->andReturn([
        'last_visible_page' => 1,
        'has_next_page' => false,
    ]);
    $jikanMock->shouldReceive('getAnimeEpisodes')->andReturn(new EpisodeCollection);
    app()->instance(JikanInterface::class, $jikanMock);

    $this->actingAs($user);

    Livewire::test(Episodes::class, ['animeId' => $anime->id, 'malId' => 102])
        ->call('toggleEpisodeStatus', 1, 'watched')
        ->call('toggleEpisodeStatus', 1, 'skipped');

    expect(EpisodeUser::where('user_id', $user->id)->where('episode_id', $episode->id)->first())
        ->status->toBe(EpisodeStatus::Skipped);
});

it('does not track episodes for guest users', function () {
    $anime = Anime::factory()->create(['mal_id' => 103]);
    Episode::factory()->create(['anime_id' => $anime->id, 'number' => 1]);

    $jikanMock = Mockery::mock(JikanInterface::class);
    $jikanMock->shouldReceive('getAnimeEpisodesPagination')->andReturn([
        'last_visible_page' => 1,
        'has_next_page' => false,
    ]);
    $jikanMock->shouldReceive('getAnimeEpisodes')->andReturn(new EpisodeCollection);
    app()->instance(JikanInterface::class, $jikanMock);

    Livewire::test(Episodes::class, ['animeId' => $anime->id, 'malId' => 103])
        ->call('toggleEpisodeStatus', 1, 'watched');

    expect(EpisodeUser::count())->toBe(0);
});
