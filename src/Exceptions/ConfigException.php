<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Exceptions;

use Bamilawal\LaravelAnypayAfrica\Exceptions\ApiException;

class ConfigException extends ApiException
{
    public static function missing(string $key): self
    {
        return new self(sprintf('Missing configuration key: %s', $key));
    }
}
