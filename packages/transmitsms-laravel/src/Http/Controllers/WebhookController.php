<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel\Http\Controllers;

use ExpertSystems\TransmitSms\Callbacks\CallbackUrlParser;
use ExpertSystems\TransmitSms\Data\DlrCallbackData;
use ExpertSystems\TransmitSms\Data\LinkHitCallbackData;
use ExpertSystems\TransmitSms\Data\ReplyCallbackData;
use ExpertSystems\TransmitSms\Exceptions\InvalidSignatureException;
use ExpertSystems\TransmitSms\Laravel\Contracts\HandlesDlrCallback;
use ExpertSystems\TransmitSms\Laravel\Contracts\HandlesLinkHitCallback;
use ExpertSystems\TransmitSms\Laravel\Contracts\HandlesReplyCallback;
use ExpertSystems\TransmitSms\Laravel\Events\DlrReceived;
use ExpertSystems\TransmitSms\Laravel\Events\LinkHitReceived;
use ExpertSystems\TransmitSms\Laravel\Events\ReplyReceived;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Handles incoming webhook callbacks from TransmitSMS.
 *
 * This controller processes DLR (Delivery Receipt), Reply, and Link Hit
 * callbacks, dispatching events and handler jobs as configured.
 */
class WebhookController extends Controller
{
    public function __construct(
        protected CallbackUrlParser $parser,
    ) {}

    /**
     * Handle DLR (Delivery Receipt) callback.
     */
    public function dlr(Request $request): Response
    {
        try {
            $parsed = $this->parser->parse($request->query->all());
        } catch (InvalidSignatureException $e) {
            return response('Invalid signature', 403);
        }

        // Create DTO from callback data
        $dlr = DlrCallbackData::fromRequest($request->query->all());

        // Always dispatch event (for global listeners)
        event(new DlrReceived($dlr, $parsed['context']));

        // Dispatch handler job if specified
        if ($parsed['handler'] !== null) {
            $this->dispatchHandler(
                $parsed['handler'],
                $dlr,
                $parsed['context'],
                config('transmitsms.webhooks.dlr.queue', 'default'),
                HandlesDlrCallback::class
            );
        }

        return response('OK', 200);
    }

    /**
     * Handle Reply callback.
     */
    public function reply(Request $request): Response
    {
        try {
            $parsed = $this->parser->parse($request->query->all());
        } catch (InvalidSignatureException $e) {
            return response('Invalid signature', 403);
        }

        // Create DTO from callback data
        $reply = ReplyCallbackData::fromRequest($request->query->all());

        // Always dispatch event (for global listeners)
        event(new ReplyReceived($reply, $parsed['context']));

        // Dispatch handler job if specified
        if ($parsed['handler'] !== null) {
            $this->dispatchHandler(
                $parsed['handler'],
                $reply,
                $parsed['context'],
                config('transmitsms.webhooks.reply.queue', 'default'),
                HandlesReplyCallback::class
            );
        }

        return response('OK', 200);
    }

    /**
     * Handle Link Hit callback.
     */
    public function linkHits(Request $request): Response
    {
        try {
            $parsed = $this->parser->parse($request->query->all());
        } catch (InvalidSignatureException $e) {
            return response('Invalid signature', 403);
        }

        // Create DTO from callback data
        $linkHit = LinkHitCallbackData::fromRequest($request->query->all());

        // Always dispatch event (for global listeners)
        event(new LinkHitReceived($linkHit, $parsed['context']));

        // Dispatch handler job if specified
        if ($parsed['handler'] !== null) {
            $this->dispatchHandler(
                $parsed['handler'],
                $linkHit,
                $parsed['context'],
                config('transmitsms.webhooks.link_hits.queue', 'default'),
                HandlesLinkHitCallback::class
            );
        }

        return response('OK', 200);
    }

    /**
     * Dispatch a handler job with the callback data.
     *
     * @param  class-string  $handlerClass
     * @param  array<string, mixed>  $context
     * @param  class-string  $expectedInterface
     */
    protected function dispatchHandler(
        string $handlerClass,
        object $callbackData,
        array $context,
        string $queue,
        string $expectedInterface,
    ): void {
        if (! class_exists($handlerClass)) {
            report(new \RuntimeException("TransmitSMS callback handler class not found: {$handlerClass}"));

            return;
        }

        // Validate handler implements the expected interface
        if (! is_a($handlerClass, $expectedInterface, true)) {
            report(new \RuntimeException(
                "TransmitSMS callback handler {$handlerClass} must implement {$expectedInterface}"
            ));

            return;
        }

        // Instantiate the job with callback data and context
        $job = new $handlerClass($callbackData, $context);

        // Set queue if the job supports it
        if (method_exists($job, 'onQueue')) {
            $job->onQueue($queue);
        }

        dispatch($job);
    }
}
