<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms;

use ExpertSystems\TransmitSms\Pagination\TransmitSmsPaginator;
use Saloon\Http\Auth\BasicAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\AcceptsJson;

class TransmitSmsConnector extends Connector implements HasPagination
{
    use AcceptsJson;

    public const BASE_URL_SMS = 'https://api.transmitsms.com';

    public const BASE_URL_MMS = 'https://api.transmitmessage.com';

    public function __construct(
        protected string $apiKey,
        protected string $apiSecret,
        protected string $baseUrl = self::BASE_URL_SMS,
        protected int $timeout = 30,
    ) {}

    /**
     * Define the base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Define default headers.
     *
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Define default config for the HTTP client.
     *
     * @return array<string, mixed>
     */
    protected function defaultConfig(): array
    {
        return [
            'timeout' => $this->timeout,
        ];
    }

    /**
     * Define the default authentication.
     */
    protected function defaultAuth(): BasicAuthenticator
    {
        return new BasicAuthenticator($this->apiKey, $this->apiSecret);
    }

    /**
     * Get the API key.
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Get the base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Set the base URL.
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Use the SMS base URL.
     */
    public function useSmsUrl(): self
    {
        return $this->setBaseUrl(self::BASE_URL_SMS);
    }

    /**
     * Use the MMS base URL.
     */
    public function useMmsUrl(): self
    {
        return $this->setBaseUrl(self::BASE_URL_MMS);
    }

    /**
     * Get the timeout.
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set the timeout.
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Create a paginator for the given request.
     *
     * @see https://docs.saloon.dev/installable-plugins/pagination
     */
    public function paginate(Request $request): TransmitSmsPaginator
    {
        return new TransmitSmsPaginator($this, $request);
    }
}
