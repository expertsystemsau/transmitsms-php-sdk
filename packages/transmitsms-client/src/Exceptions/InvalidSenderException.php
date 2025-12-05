<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

/**
 * Thrown when sender ID is invalid, inactive, or expired.
 *
 * Error code: BAD_CALLER_ID
 * HTTP status: 400
 */
class InvalidSenderException extends TransmitSmsException {}
