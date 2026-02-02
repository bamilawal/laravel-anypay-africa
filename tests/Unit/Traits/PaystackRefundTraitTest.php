<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Tests\Unit\Traits;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Bamilawal\LaravelAnypayAfrica\Services\PaystackService;

class PaystackRefundTraitTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            // no providers needed for this unit test
        ];
    }

    public function test_create_refund_calls_post_and_returns_array()
    {
        $expected = ['status' => true, 'data' => ['id' => 123]];

        Http::fake([
            'api.paystack.co/refund' => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->createRefund(['transaction' => 1641]);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function test_list_refunds_calls_get_and_returns_array()
    {
        $expected = ['status' => true, 'data' => []];

        Http::fake([
            'api.paystack.co/refund' => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->listRefunds();

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }

    public function test_fetch_refund_calls_get_and_returns_array()
    {
        $id = 999;
        $expected = ['status' => true, 'data' => ['id' => $id]];

        Http::fake([
            "api.paystack.co/refund/{$id}" => Http::response($expected, 200),
        ]);

        $service = new PaystackService();

        $result = $service->fetchRefund($id);

        $this->assertIsArray($result);
        $this->assertEquals($expected, $result);
    }
}
