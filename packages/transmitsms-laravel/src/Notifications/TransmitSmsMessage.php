<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

class TransmitSmsMessage
{
    protected string $content;

    protected ?string $to = null;

    protected ?string $from = null;

    protected ?string $sendAt = null;

    /** @var array<string, mixed> */
    protected array $options = [];

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the message content.
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the recipient phone number.
     */
    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set the sender ID.
     */
    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Schedule the message to be sent at a specific time.
     */
    public function sendAt(string $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Set additional options for the API request.
     *
     * @param  array<string, mixed>  $options
     */
    public function options(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Get the message content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the recipient phone number.
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * Get the sender ID.
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * Get the scheduled send time.
     */
    public function getSendAt(): ?string
    {
        return $this->sendAt;
    }

    /**
     * Get all options for the API request.
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
