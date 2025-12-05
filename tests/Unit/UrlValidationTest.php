<?php

declare(strict_types=1);

use ExpertSystems\TransmitSms\Exceptions\ValidationException;
use ExpertSystems\TransmitSms\Support\Url;

describe('Url validation', function () {
    describe('validate()', function () {
        it('accepts valid HTTPS URL', function () {
            expect(fn () => Url::validate('https://example.com/webhook'))->not->toThrow(ValidationException::class);
        });

        it('accepts valid HTTP URL', function () {
            expect(fn () => Url::validate('http://example.com/webhook'))->not->toThrow(ValidationException::class);
        });

        it('accepts URL with port', function () {
            expect(fn () => Url::validate('https://example.com:8080/webhook'))->not->toThrow(ValidationException::class);
        });

        it('accepts URL with query parameters', function () {
            expect(fn () => Url::validate('https://example.com/webhook?token=abc123'))->not->toThrow(ValidationException::class);
        });

        it('rejects empty URL', function () {
            expect(fn () => Url::validate(''))
                ->toThrow(ValidationException::class, 'cannot be empty');
        });

        it('rejects invalid URL format', function () {
            expect(fn () => Url::validate('not-a-url'))
                ->toThrow(ValidationException::class, 'not a valid URL');
        });

        it('rejects FTP URLs', function () {
            expect(fn () => Url::validate('ftp://example.com/file'))
                ->toThrow(ValidationException::class, 'must use HTTP or HTTPS');
        });

        it('rejects file URLs', function () {
            expect(fn () => Url::validate('file:///etc/passwd'))
                ->toThrow(ValidationException::class, 'must use HTTP or HTTPS');
        });

        it('rejects mailto URLs', function () {
            expect(fn () => Url::validate('mailto:test@example.com'))
                ->toThrow(ValidationException::class, 'must use HTTP or HTTPS');
        });

        it('includes field name in error message', function () {
            expect(fn () => Url::validate('invalid', 'dlr_callback'))
                ->toThrow(ValidationException::class, 'dlr_callback');
        });

        it('sets error code to FIELD_EMPTY for empty URLs', function () {
            try {
                Url::validate('', 'test_field');
            } catch (ValidationException $e) {
                expect($e->getErrorCode())->toBe('FIELD_EMPTY');
            }
        });

        it('sets error code to FIELD_INVALID for invalid URLs', function () {
            try {
                Url::validate('not-a-url', 'test_field');
            } catch (ValidationException $e) {
                expect($e->getErrorCode())->toBe('FIELD_INVALID');
            }
        });
    });

    describe('isValid()', function () {
        it('returns true for valid HTTPS URL', function () {
            expect(Url::isValid('https://example.com/webhook'))->toBeTrue();
        });

        it('returns true for valid HTTP URL', function () {
            expect(Url::isValid('http://example.com/webhook'))->toBeTrue();
        });

        it('returns false for empty string', function () {
            expect(Url::isValid(''))->toBeFalse();
        });

        it('returns false for invalid URL', function () {
            expect(Url::isValid('not-a-url'))->toBeFalse();
        });

        it('returns false for FTP URL', function () {
            expect(Url::isValid('ftp://example.com'))->toBeFalse();
        });
    });

    describe('validateEmail()', function () {
        it('accepts valid email', function () {
            expect(fn () => Url::validateEmail('test@example.com'))->not->toThrow(ValidationException::class);
        });

        it('accepts email with subdomain', function () {
            expect(fn () => Url::validateEmail('test@mail.example.com'))->not->toThrow(ValidationException::class);
        });

        it('accepts email with plus sign', function () {
            expect(fn () => Url::validateEmail('test+tag@example.com'))->not->toThrow(ValidationException::class);
        });

        it('rejects empty email', function () {
            expect(fn () => Url::validateEmail(''))
                ->toThrow(ValidationException::class, 'cannot be empty');
        });

        it('rejects invalid email format', function () {
            expect(fn () => Url::validateEmail('not-an-email'))
                ->toThrow(ValidationException::class, 'not a valid email');
        });

        it('rejects email without domain', function () {
            expect(fn () => Url::validateEmail('test@'))
                ->toThrow(ValidationException::class, 'not a valid email');
        });

        it('includes field name in error message', function () {
            expect(fn () => Url::validateEmail('invalid', 'replies_to_email'))
                ->toThrow(ValidationException::class, 'replies_to_email');
        });
    });

    describe('isValidEmail()', function () {
        it('returns true for valid email', function () {
            expect(Url::isValidEmail('test@example.com'))->toBeTrue();
        });

        it('returns false for empty string', function () {
            expect(Url::isValidEmail(''))->toBeFalse();
        });

        it('returns false for invalid email', function () {
            expect(Url::isValidEmail('not-an-email'))->toBeFalse();
        });
    });
});
