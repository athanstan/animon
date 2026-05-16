<?php

declare(strict_types=1);

use App\Actions\Anime\GetAnimeDetails;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Exceptions\Anime\AnimeDetailsNotFoundException;
use App\Exceptions\Anime\AnimeDetailsUnavailableException;
use App\Exceptions\Anime\AnimeIntegrationRateLimitedException;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Interfaces\JikanInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('returns cached anime details from jikan', function () {
    $dto = AnimeDTO::fromResponse([
        'mal_id' => 1,
        'title' => 'Test',
        'images' => ['jpg' => ['image_url' => 'https://example.com/x.jpg']],
    ]);

    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeById')->once()->with(1)->andReturn($dto);

    app()->instance(JikanInterface::class, $jikan);

    $action = app(GetAnimeDetails::class);

    expect($action->execute(1))->toBe($dto)
        ->and($action->execute(1))->toBe($dto);
});

it('maps not found to anime details not found', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeById')->once()->andThrow(new NotFoundException);

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeDetails::class)->execute(99))
        ->toThrow(AnimeDetailsNotFoundException::class);
});

it('maps rate limit to integration rate limited', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeById')->once()->andThrow(new RateLimitException);

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeDetails::class)->execute(99))
        ->toThrow(AnimeIntegrationRateLimitedException::class);
});

it('maps generic jikan failure to details unavailable', function () {
    $jikan = Mockery::mock(JikanInterface::class);
    $jikan->shouldReceive('getAnimeById')->once()->andThrow(new JikanException('upstream'));

    app()->instance(JikanInterface::class, $jikan);

    expect(fn () => app(GetAnimeDetails::class)->execute(99))
        ->toThrow(AnimeDetailsUnavailableException::class);
});
