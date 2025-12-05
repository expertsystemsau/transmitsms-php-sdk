<?php

namespace ExpertSystems\TransmitsmsPhpClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ExpertSystems\TransmitsmsPhpClient\TransmitsmsPhpClient
 */
class TransmitsmsPhpClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ExpertSystems\TransmitsmsPhpClient\TransmitsmsPhpClient::class;
    }
}
