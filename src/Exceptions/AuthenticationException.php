<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Exceptions;

use Bamilawal\LaravelAnypayAfrica\Exceptions\ApiException;

class AuthenticationException extends ApiException
{
    public static function missingSecret(): self
    {
        return new self('Paystack secret key is not configured. Set PAYSTACK_SECRET in your environment or publish the package config.');
    }
}
