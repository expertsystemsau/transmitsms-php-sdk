<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\TransmitSmsClient;
use Illuminate\Notifications\Notification;

class TransmitSmsChannel
{
    public function __construct(
        protected TransmitSmsClient $client
    ) {}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>|null
     *
     * @throws TransmitSmsException
     */
    public function send($notifiable, Notification $notification): ?array
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

        return $this->client->sendSms(
            $to,
            $message->getContent(),
            $message->getOptions()
        );
    }
}
