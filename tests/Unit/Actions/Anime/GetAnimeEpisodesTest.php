<?php

declare(strict_types=1);

use App\Actions\Anime\GetAnimeEpisodes;
use App\Collections\Jikan\EpisodeCollection;
use App\Exceptions\Anime\AnimeEpisodesNotFoundException;
use App\Exceptions\Anime\AnimeEpisodesUnavailableException;
use App\Exceptions\Anime\AnimeIntegrationRateLimitedException;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Interfaces\JikanInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('returns cached episodes from jikan', function () {
    $empty = new EpisodeCollection;

    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeEpisodes')->once()->with(2, 1)->andReturn($empty);

    app()->instance(JikanInterface::class, $jikan);

    $action = app(GetAnimeEpisodes::class);

    expect($action->execute(2))->toBe($empty)
        ->and($action->execute(2))->toBe($empty);
});

it('uses page-specific cache key when page is not one', function () {
    $empty = new EpisodeCollection;

    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeEpisodes')->once()->with(2, 3)->andReturn($empty);

    app()->instance(JikanInterface::class, $jikan);

    $action = app(GetAnimeEpisodes::class);

    expect($action->execute(2, 3))->toBe($empty)
        ->and($action->execute(2, 3))->toBe($empty);
});

it('maps not found to anime episodes not found', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeEpisodes')->once()->andThrow(new NotFoundException);

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeEpisodes::class)->execute(1))
        ->toThrow(AnimeEpisodesNotFoundException::class);
});

it('maps rate limit to integration rate limited', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeEpisodes')->once()->andThrow(new RateLimitException);

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeEpisodes::class)->execute(1))
        ->toThrow(AnimeIntegrationRateLimitedException::class);
});

it('maps generic jikan failure to episodes unavailable', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeEpisodes')->once()->andThrow(new JikanException('upstream'));

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeEpisodes::class)->execute(1))
        ->toThrow(AnimeEpisodesUnavailableException::class);
});
