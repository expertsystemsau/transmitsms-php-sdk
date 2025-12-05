<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('client package does not depend on Laravel')
    ->expect('ExpertSystems\TransmitSms')
    ->not->toUse(['Illuminate', 'Laravel'])
    ->ignoring('ExpertSystems\TransmitSms\Laravel');

arch('laravel package uses the client')
    ->expect('ExpertSystems\TransmitSms\Laravel')
    ->toUse('ExpertSystems\TransmitSms\TransmitSmsClient');
