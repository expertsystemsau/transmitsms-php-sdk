<?php

declare(strict_types=1);

namespace ExpertSystems\Kudosity\Requests\V2;

use ExpertSystems\Kudosity\Concerns\GuardsMessageRef;
use ExpertSystems\Kudosity\Data\V2\SmsMessageData;
use ExpertSystems\Kudosity\Exceptions\ValidationException;
use ExpertSystems\Kudosity\Requests\KudosityV2BodyRequest;
use ExpertSystems\Kudosity\Support\PhoneNumber;
use Saloon\Http\Response;

/**
 * Send a single-recipient SMS.
 *
 * `POST /v2/sms` takes exactly one recipient and has no scheduling. For
 * multiple recipients, a contact list, or a future send time, use the V1
 * bulk surface — `$client->bulk()`.
 *
 * @see https://developers.kudosity.com/reference/post_v2-sms
 */
class SendSmsV2Request extends KudosityV2BodyRequest
{
    use GuardsMessageRef;

    /**
     * @throws ValidationException If message_ref exceeds its documented maximum
     * @throws \InvalidArgumentException If the recipient carries no digits
     */
    public function __construct(
        protected string $message,
        protected string $recipient,
        protected string $sender,
        protected ?string $messageRef = null,
        protected bool $trackLinks = false,
    ) {
        self::guardMessageRef($messageRef);

        // Punctuation goes, the same as SendWhatsAppRequest and SendRcsRequest
        // have always done — a number pasted out of a CRM should not behave
        // differently depending on which channel a notification happens to use.
        //
        // No country is passed, deliberately, and for the reason spelled out at
        // length in SendWhatsAppRequest: a leading-zero local number cannot be
        // resolved to E.164 without knowing the country, and defaulting one means
        // prepending 61 to a number typed for somewhere else. The zero stays and
        // the API rejects it loudly. Do not "fix" this into a country default.
        $this->recipient = PhoneNumber::toInternational($recipient);
    }

    public function resolveEndpoint(): string
    {
        return '/v2/sms';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = [
            'message' => $this->message,
            'sender' => $this->sender,
            'recipient' => $this->recipient,
        ];

        if ($this->messageRef !== null) {
            $body['message_ref'] = $this->messageRef;
        }

        if ($this->trackLinks) {
            $body['track_links'] = true;
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): SmsMessageData
    {
        // payload() comes from UnwrapsData on the base. SMS is flat, but going
        // through it keeps every V2 request identical regardless of envelope.
        return SmsMessageData::fromArray(static::payload($response));
    }
}
