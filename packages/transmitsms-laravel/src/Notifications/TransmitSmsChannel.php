<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

use ExpertSystems\TransmitSms\Data\SmsData;
use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\Exceptions\ValidationException;
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

        try {
            // Build the SendSmsRequest (may throw ValidationException)
            $request = (new SendSmsRequest($message->getContent()))->to($to);

            // Apply sender ID: message > config > default
            $from = $message->getFrom() ?? Config::get('transmitsms.from');
            if ($from !== null && $from !== '') {
                $request->from($from);
            }

            // Apply scheduled send time if set
            if ($message->getSendAt() !== null) {
                $request->scheduledAt($message->getSendAt());
            }

            // Apply additional message options
            if ($message->getValidity() !== null) {
                $request->validity($message->getValidity());
            }

            if ($message->getCountryCode() !== null) {
                $request->countryCode($message->getCountryCode());
            }

            if ($message->getRepliesToEmail() !== null) {
                $request->repliesToEmail($message->getRepliesToEmail());
            }

            if ($message->getTrackedLinkUrl() !== null) {
                $request->trackedLinkUrl($message->getTrackedLinkUrl());
            }

            if ($message->getDlrCallback() !== null) {
                $request->dlrCallback($message->getDlrCallback());
            }

            if ($message->getReplyCallback() !== null) {
                $request->replyCallback($message->getReplyCallback());
            }

            if ($message->getLinkHitsCallback() !== null) {
                $request->linkHitsCallback($message->getLinkHitsCallback());
            }
        } catch (ValidationException $e) {
            // Re-throw validation errors as TransmitSmsException for consistent error handling
            throw new TransmitSmsException(
                $e->getMessage(),
                $e->getCode(),
                $e,
                $e->getErrorCode()
            );
        }

        // Send the request and return the DTO
        return $this->client->sms()->sendRequest($request);
    }
}
