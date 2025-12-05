<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Requests\TransmitSmsRequest;
use ExpertSystems\TransmitSms\Resources\AccountResource;
use ExpertSystems\TransmitSms\Resources\EmailSmsResource;
use ExpertSystems\TransmitSms\Resources\KeywordsResource;
use ExpertSystems\TransmitSms\Resources\ListsResource;
use ExpertSystems\TransmitSms\Resources\NumbersResource;
use ExpertSystems\TransmitSms\Resources\ReportingResource;
use ExpertSystems\TransmitSms\Resources\SmsResource;
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

    // =========================================================================
    // Resources
    // =========================================================================

    /**
     * Access account-related API operations.
     *
     * @see https://developer.transmitsms.com/#account
     */
    public function account(): AccountResource
    {
        return new AccountResource($this->connector);
    }

    /**
     * Access SMS-related API operations.
     *
     * @see https://developer.transmitsms.com/#sms
     */
    public function sms(): SmsResource
    {
        return new SmsResource($this->connector);
    }

    /**
     * Access reporting and statistics API operations.
     *
     * @see https://developer.transmitsms.com/#sms
     */
    public function reporting(): ReportingResource
    {
        return new ReportingResource($this->connector);
    }

    /**
     * Access contact lists API operations.
     *
     * @see https://developer.transmitsms.com/#lists
     */
    public function lists(): ListsResource
    {
        return new ListsResource($this->connector);
    }

    /**
     * Access virtual numbers API operations.
     *
     * @see https://developer.transmitsms.com/#numbers
     */
    public function numbers(): NumbersResource
    {
        return new NumbersResource($this->connector);
    }

    /**
     * Access keywords API operations.
     *
     * @see https://developer.transmitsms.com/#keywords
     */
    public function keywords(): KeywordsResource
    {
        return new KeywordsResource($this->connector);
    }

    /**
     * Access email SMS API operations.
     *
     * @see https://developer.transmitsms.com/#email-sms
     */
    public function emailSms(): EmailSmsResource
    {
        return new EmailSmsResource($this->connector);
    }

    // =========================================================================
    // Low-Level Request Methods
    // =========================================================================

    /**
     * Send a request and return the response.
     *
     * Use this for advanced use cases where you need direct access to the response.
     * For most cases, prefer using the resource methods (e.g., $client->account()->getBalance()).
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
     * Use this for advanced use cases where you need the raw JSON response.
     * For most cases, prefer using the resource methods which return typed DTOs.
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

    // =========================================================================
    // URL Configuration
    // =========================================================================

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
