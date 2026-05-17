<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\DataTransferObjects\Jikan\AnimeDTO;
use App\Exceptions\Anime\AnimeDetailsNotFoundException;
use App\Exceptions\Anime\AnimeDetailsUnavailableException;
use App\Exceptions\Anime\AnimeIntegrationRateLimitedException;
use App\Exceptions\Integrations\Jikan\JikanException;
use App\Exceptions\Integrations\Jikan\NotFoundException;
use App\Exceptions\Integrations\Jikan\RateLimitException;
use App\Interfaces\JikanInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

final readonly class GetAnimeDetails
{
    public function __construct(
        private JikanInterface $jikan,
    ) {}

    /**
     * Cached anime detail DTO by MAL id for the anime show page.
     *
     * @throws AnimeDetailsNotFoundException
     * @throws AnimeIntegrationRateLimitedException
     * @throws AnimeDetailsUnavailableException
     */
    public function execute(int $malId): AnimeDTO
    {
        // TODO: Check the DB for the anime details first, if not found, dispatch a job to sync from Jikan and cache data.
        try {
            return Cache::remember(
                "anime_{$malId}",
                Carbon::now()->addWeek(),
                fn (): AnimeDTO => $this->jikan->getAnimeById($malId)
            );
        } catch (NotFoundException $e) {
            throw new AnimeDetailsNotFoundException(previous: $e);
        } catch (RateLimitException $e) {
            throw new AnimeIntegrationRateLimitedException(previous: $e);
        } catch (JikanException $e) {
            throw new AnimeDetailsUnavailableException(previous: $e);
        }
    }
}
