<?php

declare(strict_types=1);

use ExpertSystems\TransmitSms\Exceptions\AccessDeniedException;
use ExpertSystems\TransmitSms\Exceptions\AuthenticationException;
use ExpertSystems\TransmitSms\Exceptions\InsufficientFundsException;
use ExpertSystems\TransmitSms\Exceptions\InvalidRecipientsException;
use ExpertSystems\TransmitSms\Exceptions\InvalidSenderException;
use ExpertSystems\TransmitSms\Exceptions\RateLimitException;
use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Exceptions\ValidationException;

describe('TransmitSmsException', function () {
    describe('exception properties', function () {
        it('stores error code', function () {
            $exception = new TransmitSmsException('Test error', 0, null, 'TEST_CODE');

            expect($exception->getErrorCode())->toBe('TEST_CODE');
            expect($exception->getMessage())->toBe('Test error');
        });

        it('allows null error code', function () {
            $exception = new TransmitSmsException('Test error');

            expect($exception->getErrorCode())->toBeNull();
        });

        it('stores previous exception', function () {
            $previous = new Exception('Previous error');
            $exception = new TransmitSmsException('Test error', 0, $previous);

            expect($exception->getPrevious())->toBe($previous);
        });
    });

    describe('specific exception types', function () {
        it('AuthenticationException extends TransmitSmsException', function () {
            $exception = new AuthenticationException('Auth failed', 401, null, 'AUTH_FAILED');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
            expect($exception->getErrorCode())->toBe('AUTH_FAILED');
        });

        it('RateLimitException extends TransmitSmsException', function () {
            $exception = new RateLimitException('Rate limit exceeded');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });

        it('ValidationException extends TransmitSmsException', function () {
            $exception = new ValidationException('Invalid field');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });

        it('InsufficientFundsException extends TransmitSmsException', function () {
            $exception = new InsufficientFundsException('Insufficient balance');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });

        it('InvalidRecipientsException extends TransmitSmsException', function () {
            $exception = new InvalidRecipientsException('Invalid recipients');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });

        it('AccessDeniedException extends TransmitSmsException', function () {
            $exception = new AccessDeniedException('Access denied');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });

        it('InvalidSenderException extends TransmitSmsException', function () {
            $exception = new InvalidSenderException('Invalid sender');

            expect($exception)->toBeInstanceOf(TransmitSmsException::class);
        });
    });
});
