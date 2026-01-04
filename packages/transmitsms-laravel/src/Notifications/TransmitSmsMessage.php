<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Notifications;

class TransmitSmsMessage
{
    protected string $content;

    protected ?string $to = null;

    protected ?string $from = null;

    protected ?string $sendAt = null;

    protected ?int $validity = null;

    protected ?string $countryCode = null;

    protected ?string $repliesToEmail = null;

    protected ?string $trackedLinkUrl = null;

    protected ?string $dlrCallback = null;

    protected ?string $replyCallback = null;

    protected ?string $linkHitsCallback = null;

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
     * Set the message validity period in minutes.
     */
    public function validity(int $validity): self
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Set the country code for the recipient number.
     */
    public function countryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Set the email address to receive replies.
     */
    public function repliesToEmail(string $repliesToEmail): self
    {
        $this->repliesToEmail = $repliesToEmail;

        return $this;
    }

    /**
     * Set the URL to track link clicks.
     */
    public function trackedLinkUrl(string $trackedLinkUrl): self
    {
        $this->trackedLinkUrl = $trackedLinkUrl;

        return $this;
    }

    /**
     * Set the delivery receipt callback URL.
     */
    public function dlrCallback(string $dlrCallback): self
    {
        $this->dlrCallback = $dlrCallback;

        return $this;
    }

    /**
     * Set the reply callback URL.
     */
    public function replyCallback(string $replyCallback): self
    {
        $this->replyCallback = $replyCallback;

        return $this;
    }

    /**
     * Set the link hits callback URL.
     */
    public function linkHitsCallback(string $linkHitsCallback): self
    {
        $this->linkHitsCallback = $linkHitsCallback;

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
     * Get the message validity period in minutes.
     */
    public function getValidity(): ?int
    {
        return $this->validity;
    }

    /**
     * Get the country code.
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Get the email address to receive replies.
     */
    public function getRepliesToEmail(): ?string
    {
        return $this->repliesToEmail;
    }

    /**
     * Get the tracked link URL.
     */
    public function getTrackedLinkUrl(): ?string
    {
        return $this->trackedLinkUrl;
    }

    /**
     * Get the delivery receipt callback URL.
     */
    public function getDlrCallback(): ?string
    {
        return $this->dlrCallback;
    }

    /**
     * Get the reply callback URL.
     */
    public function getReplyCallback(): ?string
    {
        return $this->replyCallback;
    }

    /**
     * Get the link hits callback URL.
     */
    public function getLinkHitsCallback(): ?string
    {
        return $this->linkHitsCallback;
    }
}
