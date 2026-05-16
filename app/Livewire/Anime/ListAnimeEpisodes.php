<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\SyncEpisodesFromJikan;
use App\Collections\Jikan\EpisodeCollection;
use App\Enums\EpisodeStatus;
use App\Interfaces\JikanInterface;
use App\Models\Episode;
use App\Models\EpisodeUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class ListAnimeEpisodes extends Component
{
    public int $animeId;

    public int $malId;

    public string $animeSlug;

    public int $lastPage = 1;

    public array $loadedPages = [];

    public function mount(int $animeId, int $malId, string $animeSlug): void
    {
        $this->animeId = $animeId;
        $this->malId = $malId;
        $this->animeSlug = $animeSlug;

        $pagination = $this->getPaginationFromCache();
        $this->lastPage = $pagination['last_visible_page'] ?? 1;

        $this->loadedPages = [$this->lastPage];
    }

    public function loadMore(): void
    {
        if (! $this->hasMorePages) {
            return;
        }

        $previousPage = min($this->loadedPages) - 1;

        if ($previousPage >= 1) {
            $this->loadedPages[] = $previousPage;
        }
    }

    public function toggleEpisodeStatus(int $episodeNumber, string $status): void
    {
        if (! Auth::check()) {
            return;
        }

        $episodeStatus = EpisodeStatus::from($status);
        $user = Auth::user();

        $episode = Episode::query()
            ->where('anime_id', $this->animeId)
            ->where('number', $episodeNumber)
            ->first();

        if ($episode === null) {
            return;
        }

        $existing = $episode->users()
            ->where('user_id', $user->id)
            ->first();

        if ($existing !== null && $existing->pivot->status === $episodeStatus->value) {
            $episode->users()->detach($user->id);
        } else {
            $episode->users()->syncWithoutDetaching([
                $user->id => ['status' => $episodeStatus->value],
            ]);
        }

        unset($this->userEpisodeStatuses);
    }

    #[Computed]
    public function userEpisodeStatuses(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return EpisodeUser::query()
            ->whereHas('episode', fn ($q) => $q->where('anime_id', $this->animeId))
            ->where('user_id', Auth::id())
            ->join('episodes', 'episode_user.episode_id', '=', 'episodes.id')
            ->pluck('episode_user.status', 'episodes.number')
            ->all();
    }

    #[Computed]
    public function episodes(): EpisodeCollection
    {
        $allEpisodes = collect();

        $sortedPages = collect($this->loadedPages)->sortDesc();

        foreach ($sortedPages as $page) {
            $episodes = $this->loadPageFromCache($page);
            $allEpisodes = $allEpisodes->merge($episodes);
        }

        $sortedEpisodes = $allEpisodes->sortByDesc(fn ($episode) => $episode->malId);

        return EpisodeCollection::make($sortedEpisodes->values());
    }

    #[Computed]
    public function hasMorePages(): bool
    {
        return min($this->loadedPages) > 1;
    }

    public function render()
    {
        return view('livewire.anime.list-anime-episodes');
    }

    private function getPaginationFromCache(): array
    {
        $cacheKey = "anime_{$this->malId}_episodes_pagination";

        return Cache::remember(
            $cacheKey,
            now()->addDay(),
            fn () => app(JikanInterface::class)->getAnimeEpisodesPagination($this->malId)
        );
    }

    private function loadPageFromCache(int $page): EpisodeCollection
    {
        $cacheKey = "anime_{$this->malId}_episodes_page_{$page}";

        $episodes = Cache::remember(
            $cacheKey,
            now()->addDay(),
            fn () => app(JikanInterface::class)->getAnimeEpisodes($this->malId, $page)
        );

        app(SyncEpisodesFromJikan::class)->execute($this->animeId, $episodes);

        return $episodes;
    }
}
