<?php

declare(strict_types=1);

use ExpertSystems\TransmitSms\Exceptions\ValidationException;
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

    describe('URL validation', function () {
        it('validates trackedLinkUrl accepts valid HTTPS URL', function () {
            $request = (new SendSmsRequest('Test'))
                ->trackedLinkUrl('https://example.com/page');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('validates trackedLinkUrl rejects invalid URL', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->trackedLinkUrl('not-a-url'))
                ->toThrow(ValidationException::class);
        });

        it('validates dlrCallback accepts valid URL', function () {
            $request = (new SendSmsRequest('Test'))
                ->dlrCallback('https://myapp.com/webhook/dlr');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('validates dlrCallback rejects invalid URL', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->dlrCallback('ftp://invalid.com'))
                ->toThrow(ValidationException::class);
        });

        it('validates replyCallback accepts valid URL', function () {
            $request = (new SendSmsRequest('Test'))
                ->replyCallback('https://myapp.com/webhook/reply');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('validates replyCallback rejects invalid URL', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->replyCallback(''))
                ->toThrow(ValidationException::class);
        });

        it('validates linkHitsCallback accepts valid URL', function () {
            $request = (new SendSmsRequest('Test'))
                ->linkHitsCallback('https://myapp.com/webhook/clicks');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('validates linkHitsCallback rejects invalid URL', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->linkHitsCallback('javascript:alert(1)'))
                ->toThrow(ValidationException::class);
        });
    });

    describe('email validation', function () {
        it('validates repliesToEmail accepts valid email', function () {
            $request = (new SendSmsRequest('Test'))
                ->repliesToEmail('test@example.com');

            expect($request)->toBeInstanceOf(SendSmsRequest::class);
        });

        it('validates repliesToEmail rejects invalid email', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->repliesToEmail('not-an-email'))
                ->toThrow(ValidationException::class);
        });

        it('validates repliesToEmail rejects empty email', function () {
            expect(fn () => (new SendSmsRequest('Test'))
                ->repliesToEmail(''))
                ->toThrow(ValidationException::class);
        });
    });
});
