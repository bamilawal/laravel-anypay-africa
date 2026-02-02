<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits\Paystack;

trait PaystackTransferControlTrait
{
    /**
     * Check balance
     *
     * @return array
     */
    public function checkBalance(): array
    {
        $response = $this->get('/balance');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Fetch balance ledger
     *
     * @return array
     */
    public function fetchBalanceLedger(): array
    {
        $response = $this->get('/balance/ledger');
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Resend OTP for a transfer
     *
     * @param array $payload e.g. ['transfer_code' => 'TRF_xxx', 'reason' => 'resend_otp']
     * @return array
     */
    public function resendOtp(array $payload): array
    {
        $response = $this->postJson('/transfer/resend_otp', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Disable OTP
     *
     * @param array|null $payload optional
     * @return array
     */
    public function disableOtp(array $payload = null): array
    {
        $payload = $payload ?? [];
        $response = $this->postJson('/transfer/disable_otp', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Finalize disable OTP with OTP value
     *
     * @param array $payload e.g. ['otp' => '123456']
     * @return array
     */
    public function finalizeDisableOtp(array $payload): array
    {
        $response = $this->postJson('/transfer/disable_otp_finalize', $payload);
        return $this->handleSuccessfulResponse($response);
    }

    /**
     * Enable OTP
     *
     * @param array|null $payload optional
     * @return array
     */
    public function enableOtp(array $payload = null): array
    {
        $payload = $payload ?? [];
        $response = $this->postJson('/transfer/enable_otp', $payload);
        return $this->handleSuccessfulResponse($response);
    }
}
