<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Facades;

use ExpertSystems\TransmitSms\TransmitSmsClient;
use ExpertSystems\TransmitSms\TransmitSmsConnector;
use Illuminate\Support\Facades\Facade;
use Saloon\Http\Response;

/**
 * @method static TransmitSmsConnector connector()
 * @method static Response send(\ExpertSystems\TransmitSms\Requests\TransmitSmsRequest $request)
 * @method static array sendAndGetJson(\ExpertSystems\TransmitSms\Requests\TransmitSmsRequest $request)
 * @method static TransmitSmsClient useSmsUrl()
 * @method static TransmitSmsClient useMmsUrl()
 * @method static TransmitSmsClient setBaseUrl(string $baseUrl)
 *
 * @see \ExpertSystems\TransmitSms\TransmitSmsClient
 */
class TransmitSms extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return TransmitSmsClient::class;
    }
}
