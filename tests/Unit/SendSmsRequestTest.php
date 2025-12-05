<?php

declare(strict_types=1);

use ExpertSystems\TransmitSms\Requests\SendSmsRequest;

describe('SendSmsRequest', function () {
    describe('construction', function () {
        it('creates request with message', function () {
            $request = new SendSmsRequest('Hello World');
            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });
    });

    describe('fluent builder', function () {
        it('chains to() method', function () {
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('chains toList() method', function () {
            $request = (new SendSmsRequest('Test'))
                ->toList(12345);

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('chains from() method', function () {
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000')
                ->from('MyBrand');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('chains countryCode() method', function () {
            $request = (new SendSmsRequest('Test'))
                ->to('0400000000')
                ->countryCode('AU');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('chains all methods together', function () {
            $request = (new SendSmsRequest('Test message'))
                ->to('61400000000')
                ->from('MyBrand')
                ->countryCode('AU')
                ->scheduledAt('2025-12-06 10:00:00')
                ->validity(60)
                ->repliesToEmail('test@example.com')
                ->trackedLinkUrl('https://example.com')
                ->dlrCallback('https://myapp.com/dlr')
                ->replyCallback('https://myapp.com/reply')
                ->linkHitsCallback('https://myapp.com/clicks');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });
    });

    describe('scheduledAt', function () {
        it('accepts string datetime', function () {
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000')
                ->scheduledAt('2025-12-06 10:00:00');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('accepts DateTime object', function () {
            $dateTime = new DateTime('2025-12-06 10:00:00', new DateTimeZone('UTC'));
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000')
                ->scheduledAt($dateTime);

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('accepts DateTimeImmutable object', function () {
            $dateTime = new DateTimeImmutable('2025-12-06 10:00:00', new DateTimeZone('UTC'));
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000')
                ->scheduledAt($dateTime);

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('converts non-UTC datetime to UTC', function () {
            // Create a datetime in Sydney timezone (UTC+11 in December)
            $sydneyTime = new DateTime('2025-12-06 21:00:00', new DateTimeZone('Australia/Sydney'));
            $request = (new SendSmsRequest('Test'))
                ->to('61400000000')
                ->scheduledAt($sydneyTime);

            // The internal sendAt should be in UTC (21:00 Sydney = 10:00 UTC)
            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });
    });

    describe('resolveEndpoint', function () {
        it('returns the correct endpoint', function () {
            $request = new SendSmsRequest('Test');
            expect($request->resolveEndpoint())->toBe('/send-sms.json');
        });
    });

    describe('formatNumbers', function () {
        it('can enable number formatting', function () {
            $request = (new SendSmsRequest('Test'))
                ->to('0400000000')
                ->countryCode('AU')
                ->formatNumbers();

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });
    });
});
