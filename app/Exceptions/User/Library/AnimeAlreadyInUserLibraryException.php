<?php

declare(strict_types=1);

namespace App\Exceptions\User\Library;

use App\Exceptions\User\UserException;
use Throwable;

final class AnimeAlreadyInUserLibraryException extends UserException
{
    public function __construct(string $message = 'This anime is already in your library.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return 409;
    }
}
