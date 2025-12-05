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
];
