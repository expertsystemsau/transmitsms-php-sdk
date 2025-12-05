<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

use ExpertSystems\TransmitSms\Exceptions\TransmitSmsException;
use ExpertSystems\TransmitSms\TransmitSmsClient;
use Illuminate\Notifications\Notification;
use Saloon\Http\Response;

class TransmitSmsChannel
{
    public function __construct(
        protected TransmitSmsClient $client
    ) {}

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     *
     * @throws TransmitSmsException
     */
    public function send($notifiable, Notification $notification): ?Response
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

        // TODO: Implement when SendSms request is added
        // return $this->client->send(new SendSmsRequest(
        //     to: $to,
        //     message: $message->getContent(),
        //     options: $message->getOptions()
        // ));

        throw new TransmitSmsException(
            'SendSms request not yet implemented. Please add the SendSms request class.'
        );
    }
}
