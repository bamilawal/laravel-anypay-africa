<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits\Paystack;

/**
 * Trait encapsulating Paystack Transfer endpoints.
 * Requires HttpClientTrait to be available on the consuming class.
 */
trait PaystackTransferTrait
{
    /**
     * Initiate a transfer
     *
     * @param array $payload e.g. ['source'=>'balance','reason'=>'Bonus','amount'=>100000,'recipient'=>'RCP_xxx','reference'=>'ref_xxx']
     * @return array
     */
    public function initiateTransfer(array $payload): array
    {
        $response = $this->postJson('/transfer', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Finalize a transfer (OTP)
     *
     * @param array $payload e.g. ['transfer_code' => 'TRF_xxx', 'otp' => '123456']
     * @return array
     */
    public function finalizeTransfer(array $payload): array
    {
        $response = $this->postJson('/transfer/finalize_transfer', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Initiate bulk transfer
     *
     * @param array $payload e.g. ['currency'=>'NGN','source'=>'balance','transfers'=>[...]]
     * @return array
     */
    public function initiateBulkTransfer(array $payload): array
    {
        $response = $this->postJson('/transfer/bulk', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * List transfers
     *
     * @return array
     */
    public function listTransfers(): array
    {
        $response = $this->get('/transfer');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch transfer by id or code
     *
     * @param string|int $idOrCode
     * @return array
     */
    public function fetchTransfer($idOrCode): array
    {
        $idOrCode = (string) $idOrCode;
        $response = $this->get("/transfer/{$idOrCode}");
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Verify transfer by reference
     *
     * @param string $reference
     * @return array
     */
    public function verifyTransfer(string $reference): array
    {
        $reference = urlencode($reference);
        $response = $this->get("/transfer/verify/{$reference}");
        return $this->handleSuccessfulResponse($response);
    }
}
