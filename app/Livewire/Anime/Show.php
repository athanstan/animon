<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\DataTransferObjects\Jikan\EpisodeDTO;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Interfaces\JikanInterface;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

final class Show extends Component
{
    public int $animeId;

    public function mount(int $id): void
    {
        $this->animeId = $id;
    }

    public function getAnimeProperty(): AnimeDTO
    {
        try {
            return Cache::remember(
                "anime_{$this->animeId}",
                now()->addHours(6),
                fn() => app(JikanInterface::class)->getAnimeById($this->animeId)
            );
        } catch (NotFoundException $e) {
            abort(404, 'Anime not found');
        } catch (RateLimitException $e) {
            abort(429, 'Too many requests. Please try again later.');
        } catch (JikanException $e) {
            abort(500, 'Failed to fetch anime data');
        }
    }

    public function getEpisodesProperty(): EpisodeCollection
    {
        try {
            return Cache::remember(
                "anime_{$this->animeId}_episodes",
                now()->addHours(1),
                fn() => app(JikanInterface::class)->getAnimeEpisodes($this->animeId)
            );
        } catch (NotFoundException $e) {
            abort(404, 'Episodes not found');
        } catch (RateLimitException $e) {
            abort(429, 'Too many requests. Please try again later.');
        } catch (JikanException $e) {
            abort(500, 'Failed to fetch episode data');
        }
    }

    public function getNextAiringEpisodeProperty(): ?EpisodeDTO
    {
        return $this->episodes->nextAiring();
    }

    public function render()
    {
        return view('livewire.anime.show', [
            'anime' => $this->anime,
            'episodes' => $this->episodes,
            'nextAiringEpisode' => $this->nextAiringEpisode,
        ])->layout('components.layouts.guest', [
            'title' => $this->anime->title . ' - animon.gg',
        ]);
    }
}
