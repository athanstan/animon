<?php

declare(strict_types=1);

namespace App\Exceptions\Anime;

use Throwable;

final class AnimeEpisodesNotFoundException extends AnimePageException
{
    public function __construct(
        string $message = 'Episodes not found.',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return 404;
    }
}
