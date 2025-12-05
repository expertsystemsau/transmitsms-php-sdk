<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Resources;

use ExpertSystems\TransmitSms\TransmitSmsConnector;

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
}
