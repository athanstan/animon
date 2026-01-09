<?php

declare(strict_types=1);

namespace App\Livewire\Welcome;

use App\Collections\Jikan\AnimeCollection;
use App\Enums\JikanAnimeType;
use App\Enums\JikanRating;
use App\Enums\TopAnimeFilter;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Interfaces\JikanInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
final class TopAnime extends Component
{
    public ?TopAnimeFilter $filter = null;

    public ?JikanAnimeType $type = null;

    public ?JikanRating $rating = null;

    public int $limit = 6;

    public string $sectionTitle = '🔥 Most Famous';

    public string $sectionColor = 'bg-kawaii-coral';

    /**
     * Get top anime with caching
     */
    public function getTopAnimeProperty(): AnimeCollection
    {
        $cacheKey = $this->getCacheKey();

        try {
            return Cache::remember(
                $cacheKey,
                now()->addDay(),
                fn() => app(JikanInterface::class)->getTopAnime(
                    page: 1,
                    limit: $this->limit,
                    type: $this->type,
                    filter: $this->filter,
                    rating: $this->rating,
                    sfw: true
                )
            );
        } catch (JikanException $e) {
            Log::error('Failed to fetch top anime', [
                'filter' => $this->filter?->value,
                'type' => $this->type?->value,
                'error' => $e->getMessage(),
            ]);

            // Return empty collection on error to avoid breaking the UI
            return new AnimeCollection([]);
        }
    }

    /**
     * Generate unique cache key based on filter parameters
     */
    private function getCacheKey(): string
    {
        $parts = ['top_anime'];

        if ($this->filter !== null) {
            $parts[] = $this->filter->value;
        }

        if ($this->type !== null) {
            $parts[] = $this->type->value;
        }

        if ($this->rating !== null) {
            $parts[] = $this->rating->value;
        }

        $parts[] = (string) $this->limit;

        return implode('_', $parts);
    }

    /**
     * Placeholder while loading
     */
    public function placeholder(): string
    {
        return <<<'HTML'
        <section class="relative z-10 py-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <div class="h-8 w-48 bg-surface-secondary animate-pulse rounded-lg"></div>
                    <div class="h-10 w-24 bg-surface-secondary animate-pulse rounded-lg"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-6">
                    @for ($i = 0; $i < 6; $i++)
                        <x-welcome.anime-card-skeleton />
                    @endfor
                </div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        return view('livewire.welcome.top-anime', [
            'animeList' => $this->topAnime,
        ]);
    }
}
