<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Requests;

use ExpertSystems\TransmitSms\Data\ListData;
use Saloon\Http\Response;

/**
 * Get a specific contact list.
 *
 * @see https://developer.transmitsms.com/#get-list
 */
class GetListRequest extends TransmitSmsRequest
{
    protected ?int $page = null;

    protected ?int $max = null;

    public function __construct(
        protected int $listId,
    ) {}

    public function resolveEndpoint(): string
    {
        return $this->formatEndpoint('get-list');
    }

    /**
     * Set the page number (for contacts pagination).
     */
    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set the maximum results per page.
     */
    public function max(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'list_id' => $this->listId,
        ];

        if ($this->page !== null) {
            $body['page'] = $this->page;
        }

        if ($this->max !== null) {
            $body['max'] = $this->max;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): ListData
    {
        return ListData::fromResponse($response->json());
    }
}
