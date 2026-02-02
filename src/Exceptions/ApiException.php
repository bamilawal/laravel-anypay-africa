<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Exceptions;

use Exception;

class ApiException extends Exception
{
    /**
     * Create a new ApiException from an HTTP response message and optional code
     */
    public static function fromMessage(string $message, int $code = 0): self
    {
        return new self($message, $code);
    }
}
