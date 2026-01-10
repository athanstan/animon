<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Collections\Jikan\EpisodeCollection;
use App\Interfaces\JikanInterface;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class Episodes extends Component
{
    public int $animeId;

    public int $lastPage = 1;

    public array $loadedPages = [];

    public function mount(int $animeId): void
    {
        $this->animeId = $animeId;

        // Step 1: Get pagination info (last page number)
        $pagination = $this->getPaginationFromCache();
        $this->lastPage = $pagination['last_visible_page'] ?? 1;

        // Step 2: Load the LAST page first (newest episodes)
        $this->loadedPages = [$this->lastPage];
    }

    public function loadMore(): void
    {
        if (!$this->hasMorePages) {
            return;
        }

        // Load the previous page (older episodes)
        $previousPage = min($this->loadedPages) - 1;

        if ($previousPage >= 1) {
            $this->loadedPages[] = $previousPage;
        }
    }

    private function getPaginationFromCache(): array
    {
        $cacheKey = "anime_{$this->animeId}_episodes_pagination";

        return Cache::remember(
            $cacheKey,
            now()->addDay(),
            fn() => app(JikanInterface::class)->getAnimeEpisodesPagination($this->animeId)
        );
    }

    private function loadPageFromCache(int $page): EpisodeCollection
    {
        $cacheKey = "anime_{$this->animeId}_episodes_page_{$page}";

        return Cache::remember(
            $cacheKey,
            now()->addDay(),
            fn() => app(JikanInterface::class)->getAnimeEpisodes($this->animeId, $page)
        );
    }

    #[Computed]
    public function episodes(): EpisodeCollection
    {
        $allEpisodes = collect();

        // Sort pages from highest to lowest (newest episodes first)
        $sortedPages = collect($this->loadedPages)->sortDesc();

        foreach ($sortedPages as $page) {
            $episodes = $this->loadPageFromCache($page);
            $allEpisodes = $allEpisodes->merge($episodes);
        }

        // Sort all episodes by malId descending (episode 100, 99, 98... 1)
        $sortedEpisodes = $allEpisodes->sortByDesc(fn($episode) => $episode->malId);

        return EpisodeCollection::make($sortedEpisodes->values());
    }

    #[Computed]
    public function hasMorePages(): bool
    {
        // Check if the lowest loaded page is greater than 1
        return min($this->loadedPages) > 1;
    }

    public function render()
    {
        return view('livewire.anime.episodes');
    }
}
