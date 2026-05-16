<?php

declare(strict_types=1);

use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Interfaces\JikanInterface;
use App\Livewire\Anime\ShowAnime;
use App\Models\Anime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('injects jikan on every livewire request via boot', function () {
    $anime = Anime::factory()->create(['mal_id' => 4242]);

    $details = AnimeDTO::fromResponse([
        'mal_id' => 4242,
        'title' => 'Mock Anime Title',
        'images' => ['jpg' => ['image_url' => 'https://example.com/poster.jpg']],
    ]);

    $jikanMock = Mockery::mock(JikanInterface::class);
    $jikanMock->shouldReceive('getAnimeById')->twice()->with(4242)->andReturn($details);
    $jikanMock->shouldReceive('getAnimeEpisodes')->twice()->with(4242, 1)->andReturn(new EpisodeCollection);
    app()->instance(JikanInterface::class, $jikanMock);

    $component = Livewire::test(ShowAnime::class, ['anime' => $anime]);

    $component->assertSuccessful()
        ->assertSee('Mock Anime Title', escape: false);

    Cache::flush();

    $component->call('$refresh')
        ->assertSuccessful()
        ->assertSee('Mock Anime Title', escape: false);
});
