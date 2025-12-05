<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Data;

/**
 * Keyword DTO.
 *
 * Returned by get-keywords, add-keyword endpoints.
 */
final readonly class KeywordData
{
    public function __construct(
        public int $id,
        public string $keyword,
        public string $number,
        public string $status,
        public ?string $forwardUrl = null,
        public ?string $forwardEmail = null,
        public ?int $listId = null,
        public ?string $welcomeMessage = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromResponse(array $data): self
    {
        $keyword = $data['keyword'] ?? $data;

        return new self(
            id: (int) ($keyword['id'] ?? $keyword['keyword_id'] ?? 0),
            keyword: (string) ($keyword['keyword'] ?? $keyword['word'] ?? ''),
            number: (string) ($keyword['number'] ?? ''),
            status: (string) ($keyword['status'] ?? 'active'),
            forwardUrl: $keyword['forward_url'] ?? null,
            forwardEmail: $keyword['forward_email'] ?? null,
            listId: isset($keyword['list_id']) ? (int) $keyword['list_id'] : null,
            welcomeMessage: $keyword['welcome_message'] ?? null,
        );
    }

    /**
     * Check if the keyword is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
