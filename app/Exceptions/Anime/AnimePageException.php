<?php

declare(strict_types=1);

namespace App\Exceptions\Anime;

use Exception;

/**
 * Base for anime show-page failures (Jikan-backed reads).
 *
 * Concrete types (HTTP purpose / user-facing default message):
 * - {@see AnimeDetailsNotFoundException} — 404, catalog has no anime for this MAL id
 * - {@see AnimeEpisodesNotFoundException} — 404, no episode list for this MAL id
 * - {@see AnimeIntegrationRateLimitedException} — 429, upstream rate limit
 * - {@see AnimeDetailsUnavailableException} — 500, other Jikan failure while loading details
 * - {@see AnimeEpisodesUnavailableException} — 500, other Jikan failure while loading episodes
 */
abstract class AnimePageException extends Exception
{
    abstract public function statusCode(): int;
}
