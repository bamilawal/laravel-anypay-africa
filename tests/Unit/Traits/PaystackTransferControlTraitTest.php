<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Tests\Unit\Traits;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use Bamilawal\LaravelAnypayAfrica\Services\PaystackService;
use Bamilawal\LaravelAnypayAfrica\Exceptions\PaystackException;

class PaystackTransferControlTraitTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [];
    }

    public function test_check_balance_returns_array()
    {
        $expected = ['status' => true, 'data' => [['currency' => 'NGN', 'balance' => 100000]]];

        Http::fake([
            'api.paystack.co/balance' => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->checkBalance();

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function test_fetch_balance_ledger_returns_array()
    {
        $expected = ['status' => true, 'data' => []];

        Http::fake([
            'api.paystack.co/balance/ledger' => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->fetchBalanceLedger();

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function test_resend_otp_success()
    {
        $payload = ['transfer_code' => 'TRF_123', 'reason' => 'resend_otp'];
        $expected = ['status' => true, 'message' => 'OTP resent'];

        Http::fake([
            'api.paystack.co/transfer/resend_otp' => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->resendOtp($payload);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function test_disable_finalize_enable_otp_success()
    {
        $expectedDisable = ['status' => true, 'message' => 'OTP disabled'];
        $expectedFinalize = ['status' => true, 'message' => 'OTP disable finalized'];
        $expectedEnable = ['status' => true, 'message' => 'OTP enabled'];

        Http::fake([
            'api.paystack.co/transfer/disable_otp' => Http::response($expectedDisable, 200),
            'api.paystack.co/transfer/disable_otp_finalize' => Http::response($expectedFinalize, 200),
            'api.paystack.co/transfer/enable_otp' => Http::response($expectedEnable, 200),
        ]);

        $service = new PaystackService();

        $this->assertEquals($expectedDisable, $service->disableOtp());
        $this->assertEquals($expectedFinalize, $service->finalizeDisableOtp(['otp' => '123456']));
        $this->assertEquals($expectedEnable, $service->enableOtp());
    }

    public function test_check_balance_throws_on_server_error()
    {
        Http::fake([
            'api.paystack.co/balance' => Http::response(['message' => 'Server error'], 500),
        ]);

        $this->expectException(PaystackException::class);

        $service = new PaystackService();

        $service->checkBalance();
    }
}
