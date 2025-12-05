<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

class TransmitSmsClient
{
    protected ClientInterface $httpClient;

    protected string $baseUrl = 'https://api.transmitsms.com/';

    public function __construct(
        protected string $apiKey,
        protected string $apiSecret,
        ?ClientInterface $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Send an SMS message.
     *
     * @param  string|array<string>  $to  Phone number(s) to send to
     * @param  string  $message  The message content
     * @param  array<string, mixed>  $options  Additional options (from, send_at, etc.)
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function sendSms(string|array $to, string $message, array $options = []): array
    {
        $to = is_array($to) ? implode(',', $to) : $to;

        $params = array_merge([
            'to' => $to,
            'message' => $message,
        ], $options);

        return $this->request('send-sms.json', $params);
    }

    /**
     * Get SMS delivery status/history.
     *
     * @param  string  $messageId  The message ID to check
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function getMessageStatus(string $messageId): array
    {
        return $this->request('get-message-status.json', [
            'message_id' => $messageId,
        ]);
    }

    /**
     * Get account balance.
     *
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function getBalance(): array
    {
        return $this->request('get-balance.json');
    }

    /**
     * Get SMS replies.
     *
     * @param  array<string, mixed>  $options  Filter options
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function getSmsReplies(array $options = []): array
    {
        return $this->request('get-sms-replies.json', $options);
    }

    /**
     * Get delivery reports.
     *
     * @param  array<string, mixed>  $options  Filter options
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function getDeliveryReports(array $options = []): array
    {
        return $this->request('get-delivery-reports.json', $options);
    }

    /**
     * Add a contact to a list.
     *
     * @param  int  $listId  The list ID
     * @param  string  $mobile  Mobile number
     * @param  array<string, mixed>  $fields  Additional contact fields
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function addContact(int $listId, string $mobile, array $fields = []): array
    {
        return $this->request('add-to-list.json', array_merge([
            'list_id' => $listId,
            'msisdn' => $mobile,
        ], $fields));
    }

    /**
     * Get contact lists.
     *
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    public function getLists(): array
    {
        return $this->request('get-lists.json');
    }

    /**
     * Make an API request.
     *
     * @param  string  $endpoint  The API endpoint
     * @param  array<string, mixed>  $params  Request parameters
     * @return array<string, mixed>
     *
     * @throws TransmitSmsException
     */
    protected function request(string $endpoint, array $params = []): array
    {
        try {
            $response = $this->httpClient->request('POST', $endpoint, [
                'auth' => [$this->apiKey, $this->apiSecret],
                'form_params' => $params,
            ]);

            /** @var array<string, mixed> $data */
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['error']) && $data['error']['code'] !== 'SUCCESS') {
                throw new TransmitSmsException(
                    $data['error']['description'] ?? 'Unknown API error',
                    0,
                    null,
                    $data['error']['code'] ?? null
                );
            }

            return $data;
        } catch (GuzzleException $e) {
            throw new TransmitSmsException(
                'HTTP request failed: '.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Get the HTTP client instance.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Set a custom HTTP client.
     */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }
}
