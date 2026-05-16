<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Collections\Jikan\EpisodeCollection;
use App\Exceptions\Anime\AnimeEpisodesNotFoundException;
use App\Exceptions\Anime\AnimeEpisodesUnavailableException;
use App\Exceptions\Anime\AnimeIntegrationRateLimitedException;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Interfaces\JikanInterface;
use Illuminate\Support\Facades\Cache;

final readonly class GetAnimeEpisodes
{
    public function __construct(
        private JikanInterface $jikan,
    ) {}

    /**
     * Cached episode list slice by MAL id (Jikan pagination page).
     *
     * @throws AnimeEpisodesNotFoundException
     * @throws AnimeIntegrationRateLimitedException
     * @throws AnimeEpisodesUnavailableException
     */
    public function execute(int $malId, int $page = 1): EpisodeCollection
    {
        $cacheKey = $page === 1
            ? "anime_{$malId}_episodes"
            : "anime_{$malId}_episodes_page_{$page}";

        try {
            return Cache::remember(
                $cacheKey,
                now()->addDay(),
                fn (): EpisodeCollection => $this->jikan->getAnimeEpisodes($malId, $page)
            );
        } catch (NotFoundException $e) {
            throw new AnimeEpisodesNotFoundException(previous: $e);
        } catch (RateLimitException $e) {
            throw new AnimeIntegrationRateLimitedException(previous: $e);
        } catch (JikanException $e) {
            throw new AnimeEpisodesUnavailableException(previous: $e);
        }
    }
}
