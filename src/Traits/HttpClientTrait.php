<?php

declare(strict_types=1);

namespace Bamilawal\LaravelAnypayAfrica\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Bamilawal\LaravelAnypayAfrica\Exceptions\PaystackException;
use Bamilawal\LaravelAnypayAfrica\Exceptions\AuthenticationException;

trait HttpClientTrait
{
    // Implementing classes must provide these properties
    protected string $baseUrl;
    protected string $secret;
    protected int $timeout;

    /**
     * Perform a GET request
     */
    protected function get(string $path): Response
    {
        return $this->request('get', $path);
    }

    /**
     * Perform a JSON POST request
     */
    protected function postJson(string $path, array $payload = []): Response
    {
        return $this->request('post', $path, ['json' => $payload]);
    }

    /**
     * Perform a multipart/form-data POST request
     */
    protected function postMultipart(string $path, array $payload = []): Response
    {
        // Using Http::asMultipart is not necessary for simple form fields; we'll send as form parameters
        return $this->request('post', $path, ['form_params' => $payload]);
    }

    /**
     * Low level request wrapper
     *
     * @param string $method http method: get|post
     * @param string $path API path starting with /
     * @param array $options available keys: json, form_params
     * @return Response
     */
    protected function request(string $method, string $path, array $options = []): Response
    {
        if (empty($this->secret)) {
            throw AuthenticationException::missingSecret();
        }

        $url = $this->baseUrl . '/' . ltrim($path, '/');

        $client = Http::withHeaders($this->defaultHeaders())
            ->timeout($this->timeout);

        // Decide how to send body
        if (isset($options['json'])) {
            $response = $client->{$method}($url, $options['json']);
        } elseif (isset($options['form_params'])) {
            $response = $client->{$method}($url, $options['form_params']);
        } else {
            $response = $client->{$method}($url);
        }

        // Use Laravel's HTTP response methods for better error handling
        if ($response->failed()) {
            $this->handleHttpError($response);
        }

        return $response;
    }

    /**
     * Handle successful response and return JSON data with validation
     */
    protected function handleSuccessfulResponse(Response $response): array
    {
        if ($response->successful()) {
            $data = $response->json();
            
            // Validate that we received valid JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw PaystackException::fromMessage(
                    'Invalid JSON response from API: ' . json_last_error_msg(),
                    $response->status()
                );
            }
            
            return $data ?? [];
        }
        
        // This should not happen as we handle failed responses earlier,
        // but adding as a safeguard
        $this->handleHttpError($response);
        
        return []; // This line should never be reached
    }

    /**
     * Handle HTTP errors using Laravel's response methods
     */
    protected function handleHttpError(Response $response): void
    {
        $status = $response->status();
        $reasonPhrase = $response->reason();
        
        // Get error message from response
        $errorData = $response->json();
        $message = $errorData['message'] ?? $errorData['error'] ?? $response->body();
        
        // Create detailed error message with status information
        $errorMessage = sprintf(
            'HTTP %d %s: %s',
            $status,
            $reasonPhrase,
            $message
        );

        // Handle different types of errors
        if ($response->clientError()) {
            // 4xx Client Errors
            if ($status === 401) {
                throw AuthenticationException::fromMessage($errorMessage, $status);
            } elseif ($status === 422) {
                // Validation errors - include validation details if available
                $errors = $errorData['errors'] ?? [];
                if (!empty($errors)) {
                    $validationDetails = is_array($errors) ? json_encode($errors) : (string) $errors;
                    $errorMessage .= ' | Validation errors: ' . $validationDetails;
                }
                throw PaystackException::fromMessage($errorMessage, $status);
            } else {
                throw PaystackException::fromMessage($errorMessage, $status);
            }
        } elseif ($response->serverError()) {
            // 5xx Server Errors
            throw PaystackException::fromMessage("Server Error - " . $errorMessage, $status);
        } else {
            // Other failed responses
            throw PaystackException::fromMessage($errorMessage, $status);
        }
    }

    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->secret,
            'Accept' => 'application/json',
        ];
    }
}
