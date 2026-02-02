<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits\Paystack;

trait PaystackDedicatedAccountTrait
{
    /**
     * Create a dedicated virtual account
     *
     * @param array $payload e.g. ['customer' => 481193, 'preferred_bank' => 'wema-bank']
     * @return array decoded response
     */
    public function createDedicatedAccount(array $payload): array
    {
        $response = $this->postJson('/dedicated_account', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Assign a dedicated virtual account to a recipient
     *
     * @param array $payload expected keys: email, first_name, last_name, phone, preferred_bank, country, etc.
     * @return array
     */
    public function assignDedicatedAccount(array $payload): array
    {
        $response = $this->postMultipart('/dedicated_account/assign', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * List dedicated virtual accounts
     *
     * @return array
     */
    public function listDedicatedAccounts(): array
    {
        $response = $this->get('/dedicated_account');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch a dedicated virtual account by id
     *
     * @param string|int $id
     * @return array
     */
    public function fetchDedicatedAccount($id): array
    {
        $id = (string) $id;
        $response = $this->get("/dedicated_account/{$id}");
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Requery dedicated account
     *
     * Expected params: ['account_number' => '...', 'provider_slug' => '...', 'date' => 'yyyy-mm-dd']
     *
     * @param array $params
     * @return array
     */
    public function requeryDedicatedAccount(array $params): array
    {
        $query = http_build_query($params);
        $response = $this->get('/dedicated_account/requery?' . $query);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Deactivate (delete) a dedicated account
     *
     * @param string|int $id
     * @return array
     */
    public function deactivateDedicatedAccount($id): array
    {
        $id = (string) $id;
        $response = $this->request('delete', "/dedicated_account/{$id}");
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Add a split to a dedicated account (or create with split)
     *
     * @param array $payload e.g. ['customer'=>..., 'preferred_bank'=>..., 'split_code'=>'SPL_xxx']
     * @return array
     */
    public function addSplitToDedicatedAccount(array $payload): array
    {
        $response = $this->postJson('/dedicated_account', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Remove split from a dedicated account
     *
     * Some Paystack endpoints accept a DELETE with a JSON body. We send the payload as JSON.
     *
     * @param array $payload e.g. ['account_number' => '0033322211']
     * @return array
     */
    public function removeSplitFromDedicatedAccount(array $payload): array
    {
        $response = $this->request('delete', '/dedicated_account/split', ['json' => $payload]);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch available bank providers for dedicated accounts
     *
     * @return array
     */
    public function fetchAvailableProviders(): array
    {
        $response = $this->get('/dedicated_account/available_providers');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Generic create transaction helper (keeps existing API name from package)
     *
     * @param array $data
     * @return array
     */
    public function createTransaction(array $data): array
    {
        // Paystack's transaction creation endpoints vary depending on flow; using initialize as example
        $response = $this->postJson('/transaction/initialize', $data);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Verify transaction by reference
     *
     * @param string $reference
     * @return array
     */
    public function verifyTransaction(string $reference): array
    {
        $reference = urlencode($reference);
        $response = $this->get("/transaction/verify/{$reference}");
        return $this->handleSuccessfulResponse($response);
    }
}
