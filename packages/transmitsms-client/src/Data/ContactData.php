<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Data;

/**
 * Contact DTO.
 *
 * Returned by get-contact, add-to-list endpoints.
 */
final readonly class ContactData
{
    /**
     * @param  array<string, string>  $customFields  Custom field values
     */
    public function __construct(
        public string $mobile,
        public string $firstName,
        public string $lastName,
        public string $status,
        public ?string $dateAdded = null,
        public array $customFields = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromResponse(array $data): self
    {
        $contact = $data['contact'] ?? $data;

        // Extract custom fields
        $customFields = [];
        for ($i = 1; $i <= 10; $i++) {
            $key = "field_{$i}";
            if (isset($contact[$key]) && $contact[$key] !== '') {
                $customFields[$key] = (string) $contact[$key];
            }
        }

        return new self(
            mobile: (string) ($contact['mobile'] ?? $contact['msisdn'] ?? ''),
            firstName: (string) ($contact['firstname'] ?? ''),
            lastName: (string) ($contact['lastname'] ?? ''),
            status: (string) ($contact['status'] ?? 'active'),
            dateAdded: $contact['date_added'] ?? null,
            customFields: $customFields,
        );
    }

    /**
     * Check if contact is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if contact is opted out.
     */
    public function isOptedOut(): bool
    {
        return $this->status === 'optout';
    }
}
