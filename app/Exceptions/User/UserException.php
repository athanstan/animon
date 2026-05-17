<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use Exception;

abstract class UserException extends Exception
{
    abstract public function statusCode(): int;
}
