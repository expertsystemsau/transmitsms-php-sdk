<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Facades;

use ExpertSystems\TransmitSms\TransmitSmsClient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array sendSms(string|array $to, string $message, array $options = [])
 * @method static array getMessageStatus(string $messageId)
 * @method static array getBalance()
 * @method static array getSmsReplies(array $options = [])
 * @method static array getDeliveryReports(array $options = [])
 * @method static array addContact(int $listId, string $mobile, array $fields = [])
 * @method static array getLists()
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
