<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

use Exception;
use Saloon\Http\Response;
use Throwable;

class TransmitSmsException extends Exception
{
    protected ?string $errorCode = null;

    protected ?Response $response = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        ?string $errorCode = null,
        ?Response $response = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->response = $response;
    }

    /**
     * Create an exception from a Saloon response.
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        $error = $data['error'] ?? [];

        return new self(
            message: $error['description'] ?? 'Unknown API error',
            code: $response->status(),
            errorCode: $error['code'] ?? null,
            response: $response
        );
    }

    /**
     * Get the TransmitSMS API error code.
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * Get the Saloon response if available.
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
