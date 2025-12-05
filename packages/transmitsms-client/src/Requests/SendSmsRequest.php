<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Requests;

use ExpertSystems\TransmitSms\Data\SmsData;
use Saloon\Http\Response;

/**
 * Send an SMS message.
 *
 * Supports sending to individual numbers or to a list.
 * Use the fluent builder methods to set optional parameters.
 *
 * @see https://developer.transmitsms.com/#send-sms
 */
class SendSmsRequest extends TransmitSmsRequest
{
    protected ?string $to = null;

    protected ?int $listId = null;

    protected ?string $from = null;

    protected ?string $countryCode = null;

    protected ?string $sendAt = null;

    protected ?int $validity = null;

    protected ?string $repliesToEmail = null;

    protected ?string $trackedLinkUrl = null;

    protected ?string $dlrCallback = null;

    protected ?string $replyCallback = null;

    protected ?string $linkHitsCallback = null;

    /**
     * Create a new SendSmsRequest.
     *
     * @param  string  $message  The message content (up to 612 characters)
     */
    public function __construct(
        protected string $message,
    ) {}

    /**
     * Set the recipient phone number(s).
     *
     * @param  string  $to  Single number or comma-separated numbers (up to 500)
     */
    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set the recipient list ID.
     *
     * @param  int  $listId  The list ID to send to
     */
    public function toList(int $listId): self
    {
        $this->listId = $listId;

        return $this;
    }

    /**
     * Set the sender ID.
     *
     * @param  string  $from  Virtual number, short code, or alphanumeric sender (max 11 chars)
     */
    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the country code for formatting local numbers.
     *
     * @param  string  $countryCode  2-letter ISO 3166 country code (e.g., 'AU', 'NZ', 'US')
     */
    public function countryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Schedule the message for a specific time.
     *
     * @param  string  $sendAt  ISO8601 format: YYYY-MM-DD HH:MM:SS (UTC)
     */
    public function scheduledAt(string $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Set the message validity period.
     *
     * @param  int  $minutes  Maximum time to attempt delivery (0 = maximum period)
     */
    public function validity(int $minutes): self
    {
        $this->validity = $minutes;

        return $this;
    }

    /**
     * Send replies to an email address.
     *
     * @param  string  $email  Email address to receive replies
     */
    public function repliesToEmail(string $email): self
    {
        $this->repliesToEmail = $email;

        return $this;
    }

    /**
     * Set a tracked link URL.
     *
     * Use [tracked-link] in your message to insert the shortened tracking link.
     *
     * @param  string  $url  The URL to convert to a tracking link
     */
    public function trackedLinkUrl(string $url): self
    {
        $this->trackedLinkUrl = $url;

        return $this;
    }

    /**
     * Set the delivery report callback URL.
     *
     * @param  string  $url  URL to receive delivery notifications
     */
    public function dlrCallback(string $url): self
    {
        $this->dlrCallback = $url;

        return $this;
    }

    /**
     * Set the reply callback URL.
     *
     * @param  string  $url  URL to receive reply notifications
     */
    public function replyCallback(string $url): self
    {
        $this->replyCallback = $url;

        return $this;
    }

    /**
     * Set the link hits callback URL.
     *
     * @param  string  $url  URL to receive link click notifications
     */
    public function linkHitsCallback(string $url): self
    {
        $this->linkHitsCallback = $url;

        return $this;
    }

    public function resolveEndpoint(): string
    {
        return $this->formatEndpoint('/send-sms');
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'message' => $this->message,
        ];

        if ($this->to !== null) {
            $body['to'] = $this->to;
        }

        if ($this->listId !== null) {
            $body['list_id'] = $this->listId;
        }

        if ($this->from !== null) {
            $body['from'] = $this->from;
        }

        if ($this->countryCode !== null) {
            $body['countrycode'] = $this->countryCode;
        }

        if ($this->sendAt !== null) {
            $body['send_at'] = $this->sendAt;
        }

        if ($this->validity !== null) {
            $body['validity'] = $this->validity;
        }

        if ($this->repliesToEmail !== null) {
            $body['replies_to_email'] = $this->repliesToEmail;
        }

        if ($this->trackedLinkUrl !== null) {
            $body['tracked_link_url'] = $this->trackedLinkUrl;
        }

        if ($this->dlrCallback !== null) {
            $body['dlr_callback'] = $this->dlrCallback;
        }

        if ($this->replyCallback !== null) {
            $body['reply_callback'] = $this->replyCallback;
        }

        if ($this->linkHitsCallback !== null) {
            $body['link_hits_callback'] = $this->linkHitsCallback;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): SmsData
    {
        return SmsData::fromResponse($response->json());
    }
}
