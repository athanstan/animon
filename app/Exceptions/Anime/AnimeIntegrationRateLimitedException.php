<?php

declare(strict_types=1);

namespace App\Exceptions\Anime;

use Throwable;

final class AnimeIntegrationRateLimitedException extends AnimePageException
{
    public function __construct(
        string $message = 'Too many requests. Please try again later.',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return 429;
    }
}
