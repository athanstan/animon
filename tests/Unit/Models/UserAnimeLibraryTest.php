<?php

declare(strict_types=1);

use App\Enums\UserAnimeLibraryStatus;
use App\Models\Anime;
use App\Models\User;
use App\Models\UserAnimeLibrary;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a library row with factory', function () {
    $entry = UserAnimeLibrary::factory()->create();

    expect($entry)->toBeInstanceOf(UserAnimeLibrary::class)
        ->and($entry->status)->toBeInstanceOf(UserAnimeLibraryStatus::class)
        ->and($entry->user)->toBeInstanceOf(User::class)
        ->and($entry->anime)->toBeInstanceOf(Anime::class);
});

it('casts status to enum', function () {
    $entry = UserAnimeLibrary::factory()->watching()->create();

    expect($entry->status)->toBe(UserAnimeLibraryStatus::Watching);
});

it('belongs to user and anime', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create();

    $entry = UserAnimeLibrary::factory()->create([
        'user_id' => $user->id,
        'anime_id' => $anime->id,
    ]);

    expect($entry->user->is($user))->toBeTrue()
        ->and($entry->anime->is($anime))->toBeTrue();
});

it('exposes library entries on user and anime', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create();

    UserAnimeLibrary::factory()->create([
        'user_id' => $user->id,
        'anime_id' => $anime->id,
    ]);

    $user->load('libraryAnimes');
    $anime->load('libraryUsers');

    expect($user->libraryAnimes)->toHaveCount(1)
        ->and($anime->libraryUsers)->toHaveCount(1)
        ->and($user->libraryAnimes->first()->pivot)->toBeInstanceOf(UserAnimeLibrary::class)
        ->and($user->libraryAnimes->first()->pivot->status)->toBeInstanceOf(UserAnimeLibraryStatus::class);
});

it('enforces unique user and anime pair', function () {
    $user = User::factory()->create();
    $anime = Anime::factory()->create();

    UserAnimeLibrary::factory()->create([
        'user_id' => $user->id,
        'anime_id' => $anime->id,
    ]);

    UserAnimeLibrary::factory()->create([
        'user_id' => $user->id,
        'anime_id' => $anime->id,
    ]);
})->throws(QueryException::class);
