<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Resources;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Requests\TransmitSmsRequest;
use ExpertSystems\TransmitSms\TransmitSmsConnector;
use Saloon\Http\Response;

/**
 * Base resource class for grouping related API requests.
 *
 * Resources provide a logical grouping of related API endpoints,
 * similar to controllers in MVC architecture.
 *
 * @see https://docs.saloon.dev/digging-deeper/building-sdks
 */
abstract class Resource
{
    public function __construct(
        protected TransmitSmsConnector $connector,
    ) {}

    /**
     * Send a request and return the DTO.
     *
     * This method sends the request, validates the response, and returns
     * the DTO. If the response indicates an error, it throws a
     * TransmitSmsException with the error details from the API.
     *
     * @template T
     *
     * @param  TransmitSmsRequest  $request  The request to send
     * @return T The DTO created from the response
     *
     * @throws TransmitSmsException If the API returns an error
     */
    protected function sendAndDto(TransmitSmsRequest $request): mixed
    {
        $response = $this->connector->send($request);

        $this->validateResponse($response);

        return $response->dto();
    }

    /**
     * Validate the API response and throw exception if error.
     *
     * @throws TransmitSmsException
     */
    protected function validateResponse(Response $response): void
    {
        // Check for HTTP errors (4xx, 5xx)
        if ($response->status() >= 400) {
            throw TransmitSmsException::fromResponse($response);
        }

        // Check for API-level errors in the response body
        $data = $response->json();

        if (isset($data['error']) && ($data['error']['code'] ?? 'SUCCESS') !== 'SUCCESS') {
            throw TransmitSmsException::fromResponse($response);
        }
    }
}
