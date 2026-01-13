<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TransmitSMS API Key
    |--------------------------------------------------------------------------
    |
    | Your TransmitSMS API key. You can find this in your TransmitSMS
    | account settings under API Credentials.
    |
    */
    'api_key' => env('TRANSMITSMS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | TransmitSMS API Secret
    |--------------------------------------------------------------------------
    |
    | Your TransmitSMS API secret. You can find this in your TransmitSMS
    | account settings under API Credentials.
    |
    */
    'api_secret' => env('TRANSMITSMS_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the TransmitSMS API. Use the SMS URL for SMS messages
    | and the MMS URL for MMS messages.
    |
    | Available options:
    | - https://api.transmitsms.com (SMS - default)
    | - https://api.transmitmessage.com (MMS)
    |
    */
    'base_url' => env('TRANSMITSMS_BASE_URL', 'https://api.transmitsms.com'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */
    'timeout' => env('TRANSMITSMS_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Sender ID
    |--------------------------------------------------------------------------
    |
    | The default sender ID (from number) to use when sending SMS messages.
    | This can be overridden per-message.
    |
    */
    'from' => env('TRANSMITSMS_FROM', ''),

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how the package handles incoming DLR (Delivery Receipt),
    | Reply, and Link Hit callbacks from TransmitSMS.
    |
    | When you send an SMS with callback handlers (using onDlr, onReply, or
    | onLinkHit methods), the package automatically generates signed callback
    | URLs. When TransmitSMS calls these URLs, the package verifies the
    | signature and dispatches your configured handler jobs.
    |
    */
    'webhooks' => [
        /*
        |----------------------------------------------------------------------
        | Enable Webhooks
        |----------------------------------------------------------------------
        |
        | Set to false to disable webhook route registration entirely.
        |
        */
        'enabled' => env('TRANSMITSMS_WEBHOOKS_ENABLED', true),

        /*
        |----------------------------------------------------------------------
        | Route Prefix
        |----------------------------------------------------------------------
        |
        | The URL prefix for webhook endpoints. The full URLs will be:
        | - {APP_URL}/{prefix}/dlr
        | - {APP_URL}/{prefix}/reply
        | - {APP_URL}/{prefix}/link-hits
        |
        */
        'prefix' => env('TRANSMITSMS_WEBHOOKS_PREFIX', 'webhooks/transmitsms'),

        /*
        |----------------------------------------------------------------------
        | Middleware
        |----------------------------------------------------------------------
        |
        | Middleware to apply to webhook routes. The 'api' middleware is
        | recommended to disable CSRF verification and session handling.
        |
        */
        'middleware' => ['api'],

        /*
        |----------------------------------------------------------------------
        | Signing Key
        |----------------------------------------------------------------------
        |
        | Secret key used to sign and verify callback URLs. This prevents
        | unauthorized parties from spoofing webhook requests.
        |
        | Defaults to your application's APP_KEY if not specified.
        |
        */
        'signing_key' => env('TRANSMITSMS_SIGNING_KEY'),

        /*
        |----------------------------------------------------------------------
        | DLR (Delivery Receipt) Callback
        |----------------------------------------------------------------------
        |
        | Configuration for delivery receipt callbacks. These are triggered
        | when a message is delivered, fails, or times out.
        |
        */
        'dlr' => [
            'enabled' => true,
            'path' => 'dlr',
            'queue' => env('TRANSMITSMS_DLR_QUEUE', 'default'),
        ],

        /*
        |----------------------------------------------------------------------
        | Reply Callback
        |----------------------------------------------------------------------
        |
        | Configuration for reply callbacks. These are triggered when a
        | recipient replies to your SMS message.
        |
        */
        'reply' => [
            'enabled' => true,
            'path' => 'reply',
            'queue' => env('TRANSMITSMS_REPLY_QUEUE', 'default'),
        ],

        /*
        |----------------------------------------------------------------------
        | Link Hits Callback
        |----------------------------------------------------------------------
        |
        | Configuration for link hit callbacks. These are triggered when a
        | recipient clicks a tracked link in your SMS message.
        |
        */
        'link_hits' => [
            'enabled' => true,
            'path' => 'link-hits',
            'queue' => env('TRANSMITSMS_LINK_HITS_QUEUE', 'default'),
        ],
    ],
];
