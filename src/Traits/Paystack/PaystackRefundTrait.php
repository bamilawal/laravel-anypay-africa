<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits\Paystack;

trait PaystackRefundTrait
{
    /**
     * Create a refund
     *
     * @param array $payload e.g. ['transaction' => 1641]
     * @return array
     */
    public function createRefund(array $payload): array
    {
        $response = $this->postJson('/refund', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * List refunds
     *
     * @return array
     */
    public function listRefunds(): array
    {
        $response = $this->get('/refund');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch a refund by id
     *
     * @param string|int $id
     * @return array
     */
    public function fetchRefund($id): array
    {
        $id = (string) $id;
        $response = $this->get("/refund/{$id}");
        return $this->handleSuccessfulResponse($response);
    }
}
