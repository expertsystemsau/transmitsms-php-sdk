<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

use Exception;
use Saloon\Http\Response;
use Throwable;

class TransmitSmsException extends Exception
{
    /**
     * Error code to exception class mapping.
     *
     * @var array<string, class-string<TransmitSmsException>>
     */
    protected static array $errorMap = [
        'AUTH_FAILED' => AuthenticationException::class,
        'AUTH_FAILED_NO_DATA' => AuthenticationException::class,
        'OVER_LIMIT' => RateLimitException::class,
        'FIELD_EMPTY' => ValidationException::class,
        'FIELD_INVALID' => ValidationException::class,
        'LEDGER_ERROR' => InsufficientFundsException::class,
        'RECIPIENTS_ERROR' => InvalidRecipientsException::class,
        'LIST_EMPTY' => InvalidRecipientsException::class,
        'NO_ACCESS' => AccessDeniedException::class,
        'BAD_CALLER_ID' => InvalidSenderException::class,
    ];

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
     *
     * Returns a specific exception type based on the error code.
     * For rate limit exceptions, extracts rate limit metadata from headers.
     */
    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        $error = $data['error'] ?? [];
        $errorCode = $error['code'] ?? null;
        $httpStatus = $response->status();

        // Build informative error message
        $message = $error['description'] ?? null;
        if ($message === null) {
            // Provide more context when API doesn't return a description
            $message = sprintf(
                'API request failed with HTTP %d%s',
                $httpStatus,
                $errorCode !== null ? " (error code: {$errorCode})" : ''
            );
        }

        $exceptionClass = self::$errorMap[$errorCode] ?? self::class;

        // For rate limit exceptions, use the specialized factory method
        // to extract rate limit metadata from headers
        if ($exceptionClass === RateLimitException::class) {
            return RateLimitException::fromResponse($response, $message, $errorCode);
        }

        return new $exceptionClass(
            message: $message,
            code: $httpStatus,
            errorCode: $errorCode,
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
