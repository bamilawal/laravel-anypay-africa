<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Services;
use Bamilawal\LaravelAnypayAfrica\Traits\HttpClientTrait;
use Bamilawal\LaravelAnypayAfrica\Traits\Paystack\PaystackDedicatedAccount;
use Bamilawal\LaravelAnypayAfrica\Traits\Paystack\PaystackTransferTrait;
use Bamilawal\LaravelAnypayAfrica\Traits\Paystack\PaystackTransferRecipientTrait;
use Bamilawal\LaravelAnypayAfrica\Traits\Paystack\PaystackRefundTrait;
use Bamilawal\LaravelAnypayAfrica\Traits\Paystack\PaystackTransferControlTrait;

class PaystackService
{
    use HttpClientTrait;
    use PaystackDedicatedAccount;
    use PaystackTransferTrait;
    use PaystackTransferRecipientTrait;
    use PaystackRefundTrait;
    use PaystackTransferControlTrait;

    protected string $baseUrl;
    protected string $secret;
    protected int $timeout;

    public function __construct()
    {
        $config = config('laravel-anpay-africa.paystack', []);

        $this->baseUrl = rtrim($config['base_url'] ?? 'https://api.paystack.co', '/');
        $this->secret = (string) ($config['secret'] ?? '');
        $this->timeout = (int) ($config['timeout'] ?? 10);

        if (empty($this->secret)) {
            // Keep constructor light — we'll throw when an API call is attempted.
        }
    }

}