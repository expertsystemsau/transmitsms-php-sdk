<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

use ExpertSystems\TransmitSms\Data\SmsData;
use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Requests\SendSmsRequest;
use ExpertSystems\TransmitSms\TransmitSmsClient;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class TransmitSmsChannel
{
    public function __construct(
        protected TransmitSmsClient $client
    ) {}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return SmsData|null The SMS data response, or null if no recipient
     *
     * @throws TransmitSmsException
     */
    public function send($notifiable, Notification $notification): ?SmsData
    {
        /** @var TransmitSmsMessage|string $message */
        $message = $notification->toTransmitSms($notifiable);

        if (is_string($message)) {
            $message = new TransmitSmsMessage($message);
        }

        $to = $message->getTo() ?? $notifiable->routeNotificationFor('transmitsms', $notification);

        if (! $to) {
            return null;
        }

        // Build the SendSmsRequest
        $request = (new SendSmsRequest($message->getContent()))->to($to);

        // Apply sender ID: message > config > default
        $from = $message->getFrom() ?? Config::get('transmitsms.from');
        if ($from) {
            $request->from($from);
        }

        // Apply scheduled send time if set
        $sendAt = $message->getSendAt();
        if ($sendAt) {
            $request->scheduledAt($sendAt);
        }

        // Apply additional options from the message
        $this->applyOptions($request, $message->getOptions());

        // Send the request and return the DTO
        return $this->client->sms()->sendRequest($request);
    }

    /**
     * Apply message options to the request.
     *
     * @param  array<string, mixed>  $options
     */
    protected function applyOptions(SendSmsRequest $request, array $options): void
    {
        if (isset($options['validity'])) {
            $request->validity((int) $options['validity']);
        }

        if (isset($options['country_code'])) {
            $request->countryCode($options['country_code']);
        }

        if (isset($options['replies_to_email'])) {
            $request->repliesToEmail($options['replies_to_email']);
        }

        if (isset($options['tracked_link_url'])) {
            $request->trackedLinkUrl($options['tracked_link_url']);
        }

        if (isset($options['dlr_callback'])) {
            $request->dlrCallback($options['dlr_callback']);
        }

        if (isset($options['reply_callback'])) {
            $request->replyCallback($options['reply_callback']);
        }

        if (isset($options['link_hits_callback'])) {
            $request->linkHitsCallback($options['link_hits_callback']);
        }
    }
}
