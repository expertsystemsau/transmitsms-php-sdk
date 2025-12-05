<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Resources;

use ExpertSystems\TransmitSms\Data\FormattedNumberData;
use ExpertSystems\TransmitSms\Data\SmsData;
use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Pagination\TransmitSmsPaginator;
use ExpertSystems\TransmitSms\Requests\CancelSmsRequest;
use ExpertSystems\TransmitSms\Requests\FormatNumberRequest;
use ExpertSystems\TransmitSms\Requests\GetSmsResponsesRequest;
use ExpertSystems\TransmitSms\Requests\GetUserSmsResponsesRequest;
use ExpertSystems\TransmitSms\Requests\SendSmsRequest;

/**
 * SMS resource for sending and managing SMS messages.
 *
 * @see https://developer.transmitsms.com/#sms
 */
class SmsResource extends Resource
{
    /**
     * Send an SMS message to one or more recipients.
     *
     * @param  string  $message  The message content (up to 612 characters)
     * @param  string  $to  Single number or comma-separated numbers (up to 500)
     *
     * @throws TransmitSmsException
     */
    public function send(string $message, string $to): SmsData
    {
        $request = (new SendSmsRequest($message))->to($to);

        /** @var SmsData */
        return $this->connector->send($request)->dtoOrFail();
    }

    /**
     * Send an SMS message to a list.
     *
     * @param  string  $message  The message content (up to 612 characters)
     * @param  int  $listId  The list ID to send to
     *
     * @throws TransmitSmsException
     */
    public function sendToList(string $message, int $listId): SmsData
    {
        $request = (new SendSmsRequest($message))->toList($listId);

        /** @var SmsData */
        return $this->connector->send($request)->dtoOrFail();
    }

    /**
     * Send a custom SMS request with all options.
     *
     * Use this for advanced scenarios where you need full control over the request.
     *
     * @throws TransmitSmsException
     */
    public function sendRequest(SendSmsRequest $request): SmsData
    {
        /** @var SmsData */
        return $this->connector->send($request)->dtoOrFail();
    }

    /**
     * Cancel a scheduled SMS message.
     *
     * @param  int  $messageId  The message ID to cancel
     *
     * @throws TransmitSmsException
     */
    public function cancel(int $messageId): bool
    {
        $response = $this->connector->send(new CancelSmsRequest($messageId));
        $data = $response->json();

        return ($data['error']['code'] ?? '') === 'SUCCESS';
    }

    /**
     * Format a phone number for SMS delivery.
     *
     * Converts local format numbers to international E.164 format.
     *
     * @param  string  $number  The phone number to format
     * @param  string  $countryCode  2-letter ISO country code (e.g., 'AU', 'NZ', 'US')
     *
     * @throws TransmitSmsException
     */
    public function formatNumber(string $number, string $countryCode): FormattedNumberData
    {
        $request = new FormatNumberRequest($number, $countryCode);

        /** @var FormattedNumberData */
        return $this->connector->send($request)->dtoOrFail();
    }

    /**
     * Get SMS responses/replies for a specific message.
     *
     * Returns a paginator that can be iterated to get all responses.
     *
     * @param  int  $messageId  The message ID to get responses for
     */
    public function getResponses(int $messageId): TransmitSmsPaginator
    {
        $request = GetSmsResponsesRequest::forMessage($messageId);

        return $this->connector->paginate($request);
    }

    /**
     * Get SMS responses/replies for a keyword.
     *
     * Returns a paginator that can be iterated to get all responses.
     *
     * @param  int  $keywordId  The keyword ID
     */
    public function getResponsesByKeywordId(int $keywordId): TransmitSmsPaginator
    {
        $request = GetSmsResponsesRequest::forKeywordId($keywordId);

        return $this->connector->paginate($request);
    }

    /**
     * Get SMS responses/replies for a keyword by name.
     *
     * Returns a paginator that can be iterated to get all responses.
     *
     * @param  string  $keyword  The keyword name
     * @param  string  $number  The VMN number
     */
    public function getResponsesByKeyword(string $keyword, string $number): TransmitSmsPaginator
    {
        $request = GetSmsResponsesRequest::forKeyword($keyword, $number);

        return $this->connector->paginate($request);
    }

    /**
     * Get all SMS responses/replies using a custom request.
     *
     * Use this for advanced filtering options.
     */
    public function getResponsesRequest(GetSmsResponsesRequest $request): TransmitSmsPaginator
    {
        return $this->connector->paginate($request);
    }

    /**
     * Get all SMS responses/replies for the account.
     *
     * Returns a paginator that can be iterated to get all responses.
     * By default returns responses from the last 30 days.
     */
    public function getAllResponses(): TransmitSmsPaginator
    {
        return $this->connector->paginate(new GetUserSmsResponsesRequest);
    }

    /**
     * Get all SMS responses/replies using a custom request.
     *
     * Use this for advanced filtering options.
     */
    public function getAllResponsesRequest(GetUserSmsResponsesRequest $request): TransmitSmsPaginator
    {
        return $this->connector->paginate($request);
    }
}
