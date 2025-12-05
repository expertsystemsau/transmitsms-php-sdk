<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Support;

use ExpertSystems\TransmitSms\Exceptions\ValidationException;

/**
 * URL validation utility for callback and webhook URLs.
 */
final class Url
{
    /**
     * Validate that a URL is well-formed and uses HTTP/HTTPS protocol.
     *
     * @param  string  $url  The URL to validate
     * @param  string  $fieldName  The field name for error messages
     *
     * @throws ValidationException If the URL is invalid
     */
    public static function validate(string $url, string $fieldName = 'url'): void
    {
        if ($url === '') {
            throw new ValidationException(
                message: "The {$fieldName} cannot be empty",
                errorCode: 'FIELD_EMPTY'
            );
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new ValidationException(
                message: "The {$fieldName} is not a valid URL: {$url}",
                errorCode: 'FIELD_INVALID'
            );
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (! in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
            throw new ValidationException(
                message: "The {$fieldName} must use HTTP or HTTPS protocol: {$url}",
                errorCode: 'FIELD_INVALID'
            );
        }
    }

    /**
     * Check if a URL is valid without throwing exceptions.
     *
     * @param  string  $url  The URL to validate
     * @return bool True if the URL is valid
     */
    public static function isValid(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array(strtolower((string) $scheme), ['http', 'https'], true);
    }

    /**
     * Validate an email address.
     *
     * @param  string  $email  The email address to validate
     * @param  string  $fieldName  The field name for error messages
     *
     * @throws ValidationException If the email is invalid
     */
    public static function validateEmail(string $email, string $fieldName = 'email'): void
    {
        if ($email === '') {
            throw new ValidationException(
                message: "The {$fieldName} cannot be empty",
                errorCode: 'FIELD_EMPTY'
            );
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationException(
                message: "The {$fieldName} is not a valid email address: {$email}",
                errorCode: 'FIELD_INVALID'
            );
        }
    }

    /**
     * Check if an email address is valid without throwing exceptions.
     *
     * @param  string  $email  The email address to validate
     * @return bool True if the email is valid
     */
    public static function isValidEmail(string $email): bool
    {
        if ($email === '') {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
