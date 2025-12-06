<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Resources;

use ExpertSystems\TransmitSms\Data\BalanceData;
use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Requests\GetBalanceRequest;

/**
 * Account resource for account-related API operations.
 *
 * @see https://developer.transmitsms.com/#account
 */
class AccountResource extends Resource
{
    /**
     * Get the account balance.
     *
     * Returns the current account balance and currency.
     *
     * @throws TransmitSmsException
     *
     * @see https://developer.transmitsms.com/#get-balance
     */
    public function getBalance(): BalanceData
    {
        $request = new GetBalanceRequest;

        /** @var BalanceData */
        return $this->connector->send($request)->dtoOrFail();
    }
}
