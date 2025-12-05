<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Data;

/**
 * Data transfer object for format-number response.
 */
final readonly class FormattedNumberData
{
    /**
     * Number type constants.
     */
    public const TYPE_LANDLINE = 0;

    public const TYPE_MOBILE = 1;

    public const TYPE_INVALID = 10;

    public function __construct(
        public int $countryCode,
        public int $nationalNumber,
        public int $international,
        public int $type,
        public bool $isValid,
    ) {}

    /**
     * Create from API response array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromResponse(array $data): self
    {
        $number = $data['number'] ?? $data;

        return new self(
            countryCode: (int) $number['countrycode'],
            nationalNumber: (int) $number['nationalnumber'],
            international: (int) $number['international'],
            type: (int) $number['type'],
            isValid: (bool) $number['isValid'],
        );
    }

    /**
     * Check if this is a mobile number.
     */
    public function isMobile(): bool
    {
        return $this->type === self::TYPE_MOBILE;
    }

    /**
     * Check if this is a landline number.
     */
    public function isLandline(): bool
    {
        return $this->type === self::TYPE_LANDLINE;
    }

    /**
     * Get the international number as a string.
     */
    public function getInternationalString(): string
    {
        return (string) $this->international;
    }
}
