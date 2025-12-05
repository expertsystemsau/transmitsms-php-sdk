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
    | Default Sender ID
    |--------------------------------------------------------------------------
    |
    | The default sender ID (from number) to use when sending SMS messages.
    | This can be overridden per-message.
    |
    */
    'from' => env('TRANSMITSMS_FROM', ''),
];
