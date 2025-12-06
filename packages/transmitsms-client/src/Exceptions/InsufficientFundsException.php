<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

/**
 * Thrown when account has insufficient funds.
 *
 * Error code: LEDGER_ERROR
 * HTTP status: 400
 */
class InsufficientFundsException extends TransmitSmsException {}
