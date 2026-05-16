<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\Anime\GetAnimeDetails;
use App\Actions\Anime\GetAnimeEpisodes;
use App\Collections\Jikan\EpisodeCollection;
use App\DataTransferObjects\Jikan\AnimeDTO;
use App\DataTransferObjects\Jikan\EpisodeDTO;
use App\Models\Anime;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Anibaku')]
final class ShowAnime extends Component
{
    public Anime $anime;

    public int $animeId;

    protected GetAnimeDetails $getAnimeDetails;

    protected GetAnimeEpisodes $getAnimeEpisodes;

    public function boot(GetAnimeDetails $getAnimeDetails, GetAnimeEpisodes $getAnimeEpisodes): void
    {
        $this->getAnimeDetails = $getAnimeDetails;
        $this->getAnimeEpisodes = $getAnimeEpisodes;
    }

    public function mount(Anime $anime): void
    {
        $this->anime = $anime;
        $this->animeId = $anime->mal_id;
    }

    #[Computed]
    public function animeDetails(): AnimeDTO
    {
        return $this->getAnimeDetails->execute($this->animeId);
    }

    #[Computed]
    public function episodes(): EpisodeCollection
    {
        return $this->getAnimeEpisodes->execute($this->animeId);
    }

    #[Computed]
    public function nextAiringEpisode(): ?EpisodeDTO
    {
        return $this->episodes->nextAiring();
    }

    public function render(): View
    {
        return view('livewire.anime.show-anime', [
            'animeDetails' => $this->animeDetails,
            'episodes' => $this->episodes,
            'nextAiringEpisode' => $this->nextAiringEpisode,
        ]);
    }
}
