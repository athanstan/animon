<?php

declare(strict_types=1);

namespace App\Livewire\Episode;

use App\Actions\SyncEpisode;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class ShowEpisode extends Component
{
    public Anime $anime;

    public Episode $episode;

    public function mount(Anime $anime, int $number, SyncEpisode $syncEpisode): void
    {
        $this->anime = $anime;

        try {
            $this->episode = $syncEpisode->execute($anime, $number);
        } catch (NotFoundException) {
            abort(404);
        } catch (RateLimitException) {
            abort(429, 'Too many requests. Please try again later.');
        } catch (JikanException) {
            abort(503, 'Failed to load episode data.');
        }
    }

    public function render(): View
    {
        $title = ($this->episode->title_romanji ?? $this->episode->title).' · '.$this->anime->title.' — Anibaku';

        return view('livewire.episode.show-episode', [
            'anime' => $this->anime,
            'episode' => $this->episode,
        ])->layout('components.layouts.guest', ['title' => $title]);
    }
}
