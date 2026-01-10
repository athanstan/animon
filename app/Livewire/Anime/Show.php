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
use App\Models\Anime;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

final class Show extends Component
{
    public Anime $anime;
    public int $animeId;

    public function mount(Anime $anime): void
    {
        $this->anime = $anime;
        $this->animeId = $anime->mal_id;
    }

    public function getAnimeDetailsProperty(): AnimeDTO
    {
        try {
            return Cache::remember(
                "anime_{$this->animeId}",
                now()->addWeek(),
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
                now()->addDay(),
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
            'animeDetails' => $this->animeDetails,
            'episodes' => $this->episodes,
            'nextAiringEpisode' => $this->nextAiringEpisode,
        ])->layout('components.layouts.guest', [
            'title' => $this->anime->title . ' - animon.gg',
        ]);
    }
}
