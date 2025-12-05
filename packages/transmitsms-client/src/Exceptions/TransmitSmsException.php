<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

use Exception;
use Throwable;

class TransmitSmsException extends Exception
{
    protected ?string $errorCode;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        ?string $errorCode = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
    }

    /**
     * Get the TransmitSMS API error code.
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
}
