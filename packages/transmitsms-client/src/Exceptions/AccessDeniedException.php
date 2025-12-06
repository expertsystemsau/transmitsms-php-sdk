<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Exceptions;

/**
 * Thrown when access to a resource is denied.
 *
 * Error code: NO_ACCESS
 * HTTP status: 400
 */
class AccessDeniedException extends TransmitSmsException {}
