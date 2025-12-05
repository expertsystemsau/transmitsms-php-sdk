<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Pagination;

use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

/**
 * Custom paginator for TransmitSMS API responses.
 *
 * The TransmitSMS API uses page-based pagination with:
 * - page: Current page number
 * - max: Items per page
 * - Response contains: page.count, page.number, total, responses[]
 *
 * @see https://docs.saloon.dev/installable-plugins/pagination/paged-pagination
 */
class TransmitSmsPaginator extends PagedPaginator
{
    /**
     * The key containing the items in the response.
     */
    protected string $itemsKey = 'responses';

    /**
     * Check if this is the last page.
     */
    protected function isLastPage(Response $response): bool
    {
        $data = $response->json();

        // If no responses, we're done
        if (empty($data[$this->itemsKey])) {
            return true;
        }

        // Check if we've retrieved all items
        $total = $data['total'] ?? 0;
        $pageCount = $data['page']['count'] ?? 0;
        $pageNumber = $data['page']['number'] ?? 1;

        // Calculate total items retrieved so far
        // page.count is items on current page, page.number is current page
        $perPage = $this->perPageLimit ?? $pageCount;

        return ($pageNumber * $perPage) >= $total;
    }

    /**
     * Get the items from the page.
     *
     * @return array<int, mixed>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        return $response->json($this->itemsKey) ?? [];
    }

    /**
     * Apply pagination parameters to the request.
     */
    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('page', $this->currentPage + 1);

        if ($this->perPageLimit !== null) {
            $request->query()->add('max', $this->perPageLimit);
        }

        return $request;
    }

    /**
     * Set the items key for responses that use different keys.
     */
    public function setItemsKey(string $key): self
    {
        $this->itemsKey = $key;

        return $this;
    }
}
