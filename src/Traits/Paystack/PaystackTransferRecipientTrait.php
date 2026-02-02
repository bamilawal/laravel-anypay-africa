<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits\Paystack;

trait PaystackTransferRecipientTrait
{
    /**
     * Create a transfer recipient
     *
     * @param array $payload e.g. ['type'=>'nuban','name'=>'Tolu','account_number'=>'010...','bank_code'=>'058','currency'=>'NGN']
     * @return array
     */
    public function createTransferRecipient(array $payload): array
    {
        $response = $this->postJson('/transferrecipient', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Bulk create transfer recipients
     *
     * @param array $payload e.g. ['batch'=>[...]]
     * @return array
     */
    public function bulkCreateTransferRecipients(array $payload): array
    {
        $response = $this->postJson('/transferrecipient/bulk', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * List transfer recipients
     *
     * @return array
     */
    public function listTransferRecipients(): array
    {
        $response = $this->get('/transferrecipient');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch a transfer recipient by id or code
     *
     * @param string|int $idOrCode
     * @return array
     */
    public function fetchTransferRecipient($idOrCode): array
    {
        $idOrCode = (string) $idOrCode;
        $response = $this->get("/transferrecipient/{$idOrCode}");
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Update a transfer recipient
     *
     * @param string|int $idOrCode
     * @param array $payload
     * @return array
     */
    public function updateTransferRecipient($idOrCode, array $payload): array
    {
        $idOrCode = (string) $idOrCode;
        $response = $this->request('put', "/transferrecipient/{$idOrCode}", ['json' => $payload]);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Delete a transfer recipient
     *
     * @param string|int $idOrCode
     * @return array
     */
    public function deleteTransferRecipient($idOrCode): array
    {
        $idOrCode = (string) $idOrCode;
        $response = $this->request('delete', "/transferrecipient/{$idOrCode}");
        return $this->handleSuccessfulResponse($response);
    }
}
