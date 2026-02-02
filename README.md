# Laravel Anypay Africa — Paystack client

This package wraps several African payment providers. This README focuses on how to use the Paystack client exposed by the `Paystack` facade (backed by `Bamilawal\LaravelAnypayAfrica\Services\PaystackService`).

## Installation

Require this package via composer (if you are using it as local package, add it to your `composer.json` repositories or use path repository):

    composer require bamilawal/laravel-anypay-africa

Laravel's package discovery will automatically register the service provider and facades declared in `composer.json`.

## Configuration

After installing, publish the package config (if you want to override defaults):

    php artisan vendor:publish --provider="Bamilawal\LaravelAnypayAfrica\Providers\LaravelAnypayAfricaServiceProvider" --tag=config

Then configure your Paystack credentials in your `.env` file or the published config file. The package expects the following config keys under `laravel-anpay-africa.paystack`:

- `base_url` - Paystack API base (default: `https://api.paystack.co`)
- `secret` - Your Paystack secret key (starts with `sk_live_` or `sk_test_`)
- `timeout` - HTTP request timeout (seconds)

Example `.env` entries:

    PAYSTACK_BASE_URL=https://api.paystack.co
    PAYSTACK_SECRET=sk_test_xxx
    PAYSTACK_TIMEOUT=30

If you don't publish the config, the package will read from `config('laravel-anpay-africa.paystack')` so make sure those keys are present in your published file or bound environment.

## Facade / Service usage

The package exposes a facade named `Paystack` which proxies to the `PaystackService`. Use it like this from anywhere in your Laravel app (controllers, jobs, commands, etc.):

use the facade directly:

```php
use Paystack;

// Create a dedicated virtual account (DVA)
$response = Paystack::createDedicatedAccount([
    'account_number' => '1234567890',
    'bank_code' => '058',
    'preferred_name' => 'Acme Receivables',
]);

// The facade returns an array decoded from Paystack's JSON response.
if (!empty($response['status']) && $response['status'] === true) {
    $data = $response['data'];
    // handle $data
}
```

Or inject the service via constructor (type-hint the interface or concrete service):

```php
use Bamilawal\LaravelAnypayAfrica\Services\PaystackService;

class MyController {
    public function __construct(PaystackService $paystack) {
        $this->paystack = $paystack;
    }

    public function store() {
        $tx = $this->paystack->createTransaction([...]);
    }
}
```

### Common Paystack actions

The `Paystack` facade exposes methods grouped into traits inside the `PaystackService`. Below are common examples. Each method performs an HTTP call to Paystack and returns the decoded JSON payload as an array. On HTTP 4xx/5xx errors the package throws `Bamilawal\LaravelAnypayAfrica\Exceptions\PaystackException` (or more specific exceptions).

- Dedicated Virtual Accounts (DVA)
  - createDedicatedAccount(array $payload)
  - assignDedicatedAccount(array $payload)
  - listDedicatedAccounts(array $query = [])
  - fetchDedicatedAccount(string $idOrCode)
  - requeryDedicatedAccount(string $idOrCode)
  - deactivateDedicatedAccount(string $idOrCode)
  - addSplitToDedicatedAccount(string $accountId, array $payload)
  - removeSplitFromDedicatedAccount(string $accountId, array $payload)
  - fetchAvailableProviders(array $query = [])
  - createTransaction(array $payload)
  - verifyTransaction(string $reference)

- Transfers
  - initiateTransfer(array $payload)
  - finalizeTransfer(array $payload)
  - initiateBulkTransfer(array $payload)
  - listTransfers(array $query = [])
  - fetchTransfer(string $idOrCode)
  - verifyTransfer(string $reference)

- Transfer Recipients
  - createTransferRecipient(array $payload)
  - bulkCreateTransferRecipients(array $payload)
  - listTransferRecipients(array $query = [])
  - fetchTransferRecipient(string $idOrCode)
  - updateTransferRecipient(string $idOrCode, array $payload)
  - deleteTransferRecipient(string $idOrCode)

- Refunds
  - createRefund(array $payload)
  - listRefunds(array $query = [])
  - fetchRefund(string $idOrCode)

- Transfer Control (balance, ledger, OTP)
  - checkBalance()
  - fetchBalanceLedger(array $query = [])
  - resendOtp(array $payload)
  - disableOtp(array $payload)
  - finalizeDisableOtp(array $payload)
  - enableOtp(array $payload)

## Error handling

All methods throw typed exceptions in failure cases:

- `Bamilawal\LaravelAnypayAfrica\Exceptions\AuthenticationException` - when the `secret` config is missing or invalid.
- `Bamilawal\LaravelAnypayAfrica\Exceptions\ConfigException` - when required config keys are missing.
- `Bamilawal\LaravelAnypayAfrica\Exceptions\PaystackException` - for HTTP 4xx/5xx responses and unexpected API failures.
- `Bamilawal\LaravelAnypayAfrica\Exceptions\ApiException` - base API exception for other cases.

Wrap calls in try/catch to handle gracefully:

```php
use Bamilawal\LaravelAnypayAfrica\Exceptions\PaystackException;

try {
    $res = Paystack::createTransaction(['amount' => 10000, 'email' => 'foo@example.com']);
} catch (PaystackException $e) {
    // Log and display friendly message
    Log::error('Paystack error: ' . $e->getMessage(), ['exception' => $e]);
    throw $e; // or return error response
}
```

## Testing

You can unit test your code by mocking Laravel's HTTP client. The package unit tests use `Illuminate\Support\Facades\Http` to fake responses. Example:

```php
use Illuminate\Support\Facades\Http;

Http::fake([
    'api.paystack.co/*' => Http::response(['status' => true, 'data' => ['id' => 1]], 200),
]);

$response = Paystack::createTransaction([...]);
$this->assertEquals(true, $response['status']);
```

For package tests, run:

    ./vendor/bin/phpunit --configuration phpunit.xml.dist

## Contributing

PRs welcome. Please add unit tests when adding new behaviour.

## License

MIT
