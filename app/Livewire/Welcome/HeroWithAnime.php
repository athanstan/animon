<?php

declare(strict_types=1);

namespace App\Livewire\Welcome;

use App\Actions\FindOrCreateAnimeFromMalId;
use App\Collections\Jikan\AnimeCollection;
use App\Enums\JikanAnimeType;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Interfaces\JikanInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
final class HeroWithAnime extends Component
{
    /**
     * Get random top anime for scattered display
     */
    public function getRandomAnimeProperty(): AnimeCollection
    {
        $cacheKey = 'hero_random_anime';

        try {
            return Cache::remember(
                $cacheKey,
                now()->addWeek(),
                function () {
                    $anime = app(JikanInterface::class)->getTopAnime(
                        page: 1,
                        limit: 15,
                        type: JikanAnimeType::TV,
                        sfw: true
                    );

                    // Shuffle for random display
                    return new AnimeCollection($anime->shuffle()->take(10)->all());
                }
            );
        } catch (JikanException $e) {
            Log::error('Failed to fetch hero anime', [
                'error' => $e->getMessage(),
            ]);

            return new AnimeCollection([]);
        }
    }

    public function placeholder(): string
    {
        return <<<'HTML'
        <section class="relative py-16 md:py-24 overflow-hidden">
            <div class="container mx-auto px-4 text-center relative z-10">
                <div class="h-12 w-64 mx-auto bg-surface-secondary animate-pulse rounded-lg mb-6"></div>
                <div class="h-32 w-full max-w-3xl mx-auto bg-surface-secondary animate-pulse rounded-lg mb-8"></div>
                <div class="h-16 w-48 mx-auto bg-surface-secondary animate-pulse rounded-xl"></div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        $animeList = $this->randomAnime;
        $findOrCreateAction = new FindOrCreateAnimeFromMalId();

        // Map DTOs to models with slugs
        $animeWithSlugs = $animeList->map(function ($animeDto) use ($findOrCreateAction) {
            $animeModel = $findOrCreateAction->execute($animeDto->malId, $animeDto->title);

            return (object) [
                'slug' => $animeModel->slug,
                'title' => $animeDto->title,
                'images' => $animeDto->images,
            ];
        });

        return view('livewire.welcome.hero-with-anime', [
            'animeList' => $animeWithSlugs,
        ]);
    }
}
