<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Requests\GetBalanceRequest;
use ExpertSystems\TransmitSms\Requests\TransmitSmsRequest;
use Saloon\Http\Response;

class TransmitSmsClient
{
    protected TransmitSmsConnector $connector;

    /**
     * Create a new TransmitSMS client instance.
     *
     * @param  string  $apiKey  Your TransmitSMS API key
     * @param  string  $apiSecret  Your TransmitSMS API secret
     * @param  string  $baseUrl  The base URL for the API (defaults to SMS API)
     * @param  int  $timeout  Request timeout in seconds
     */
    public function __construct(
        string $apiKey,
        string $apiSecret,
        string $baseUrl = TransmitSmsConnector::BASE_URL_SMS,
        int $timeout = 30,
    ) {
        $this->connector = new TransmitSmsConnector(
            apiKey: $apiKey,
            apiSecret: $apiSecret,
            baseUrl: $baseUrl,
            timeout: $timeout,
        );
    }

    /**
     * Create client from an existing connector.
     */
    public static function fromConnector(TransmitSmsConnector $connector): self
    {
        $client = new self(
            apiKey: $connector->getApiKey(),
            apiSecret: '',
            baseUrl: $connector->getBaseUrl(),
            timeout: $connector->getTimeout(),
        );
        $client->connector = $connector;

        return $client;
    }

    /**
     * Get the underlying connector.
     */
    public function connector(): TransmitSmsConnector
    {
        return $this->connector;
    }

    /**
     * Send a request and return the response.
     *
     * @throws TransmitSmsException
     */
    public function send(TransmitSmsRequest $request): Response
    {
        $response = $this->connector->send($request);

        $this->validateResponse($response);

        return $response;
    }

    /**
     * Send a request and return the JSON data as an array.
     *
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function sendAndGetJson(TransmitSmsRequest $request): array
    {
        return $this->send($request)->json();
    }

    // =========================================================================
    // Account Methods
    // =========================================================================

    /**
     * Get the account balance.
     *
     * Returns the current account balance and currency.
     *
     * @return array{balance: float, currency: string, error: array{code: string, description: string}}
     *
     * @throws TransmitSmsException
     */
    public function getBalance(): array
    {
        return $this->sendAndGetJson(new GetBalanceRequest);
    }

    // =========================================================================
    // Response Validation
    // =========================================================================

    /**
     * Validate the API response and throw exception if error.
     *
     * @throws TransmitSmsException
     */
    protected function validateResponse(Response $response): void
    {
        // Check for HTTP errors (4xx, 5xx)
        if ($response->failed()) {
            throw TransmitSmsException::fromResponse($response);
        }

        // Check for API-level errors in the response body
        $data = $response->json();

        if (isset($data['error']) && ($data['error']['code'] ?? 'SUCCESS') !== 'SUCCESS') {
            throw TransmitSmsException::fromResponse($response);
        }
    }

    /**
     * Use the SMS base URL.
     */
    public function useSmsUrl(): self
    {
        $this->connector->useSmsUrl();

        return $this;
    }

    /**
     * Use the MMS base URL.
     */
    public function useMmsUrl(): self
    {
        $this->connector->useMmsUrl();

        return $this;
    }

    /**
     * Set a custom base URL.
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->connector->setBaseUrl($baseUrl);

        return $this;
    }
}
