<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

/**
 * Thrown when API rate limit is exceeded.
 *
 * Error code: OVER_LIMIT
 * HTTP status: 429
 *
 * Default rate limit is 15 calls per second.
 */
class RateLimitException extends TransmitSmsException {}
