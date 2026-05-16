<?php

declare(strict_types=1);

namespace App\Exceptions\Anime;

use Throwable;

final class AnimeEpisodesUnavailableException extends AnimePageException
{
    public function __construct(
        string $message = 'Failed to fetch episode data.',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return 500;
    }
}
