# TransmitSMS PHP Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/expertsystemsau/transmitsms-php-client.svg?style=flat-square)](https://packagist.org/packages/expertsystemsau/transmitsms-php-client)
[![Total Downloads](https://img.shields.io/packagist/dt/expertsystemsau/transmitsms-php-client.svg?style=flat-square)](https://packagist.org/packages/expertsystemsau/transmitsms-php-client)
[![License](https://img.shields.io/packagist/l/expertsystemsau/transmitsms-php-client.svg?style=flat-square)](https://packagist.org/packages/expertsystemsau/transmitsms-php-client)

A framework-agnostic PHP client for the [TransmitSMS API](https://transmitsms.com/).

## Installation

```bash
composer require expertsystemsau/transmitsms-php-client
```

## Usage

```php
use ExpertSystems\TransmitSms\TransmitSmsClient;

$client = new TransmitSmsClient('your-api-key', 'your-api-secret');

// Send an SMS
$response = $client->sendSms('+61400000000', 'Hello from TransmitSMS!');

// Send to multiple recipients
$response = $client->sendSms(['+61400000000', '+61400000001'], 'Bulk message');

// Send with options
$response = $client->sendSms('+61400000000', 'Scheduled message', [
    'from' => 'MySenderID',
    'send_at' => '2024-12-25 09:00:00',
]);

// Check message status
$status = $client->getMessageStatus('message-id');

// Get account balance
$balance = $client->getBalance();

// Get SMS replies
$replies = $client->getSmsReplies();

// Get delivery reports
$reports = $client->getDeliveryReports();

// Manage contacts
$lists = $client->getLists();
$client->addContact(123, '+61400000000', ['first_name' => 'John']);
```

## DLR & Reply Callbacks

The client provides utilities for handling DLR (Delivery Receipt) and Reply callbacks with signed URLs.

### Setting Up Callback URLs

```php
use ExpertSystems\TransmitSms\TransmitSmsConnector;
use ExpertSystems\TransmitSms\TransmitSmsClient;
use ExpertSystems\TransmitSms\Requests\SendSmsRequest;
use ExpertSystems\TransmitSms\Callbacks\CallbackUrlBuilder;
use ExpertSystems\TransmitSms\Callbacks\CallbackType;

// Create connector and client
$connector = new TransmitSmsConnector(
    apiKey: 'your-api-key',
    apiSecret: 'your-api-secret'
);
$client = new TransmitSmsClient($connector);

// Create URL builder with your webhook base URL and signing key
$urlBuilder = new CallbackUrlBuilder(
    baseUrl: 'https://myapp.com/webhooks/sms',
    signingKey: 'your-secret-signing-key'
);

// Send SMS with callbacks
$request = (new SendSmsRequest('Your order has shipped!'))
    ->to('61400000000')
    ->from('MYSTORE')
    ->dlrCallback(
        $urlBuilder->build(
            type: CallbackType::DLR,
            handler: 'App\\Webhooks\\OrderDlrHandler',
            context: ['order_id' => 123]
        )
    )
    ->replyCallback(
        $urlBuilder->build(
            type: CallbackType::REPLY,
            handler: 'App\\Webhooks\\OrderReplyHandler',
            context: ['order_id' => 123]
        )
    );

$result = $client->sms()->sendRequest($request);
```

### Handling Incoming Callbacks

In your webhook endpoint, parse and verify the callback:

```php
use ExpertSystems\TransmitSms\Callbacks\CallbackUrlParser;
use ExpertSystems\TransmitSms\Data\DlrCallbackData;
use ExpertSystems\TransmitSms\Data\ReplyCallbackData;
use ExpertSystems\TransmitSms\Exceptions\InvalidSignatureException;

$parser = new CallbackUrlParser('your-secret-signing-key');

try {
    // Parse and verify signature
    $parsed = $parser->parse($_GET);

    // Create DTO from callback data
    $dlr = DlrCallbackData::fromRequest($_GET);

    // Access handler and context
    $handlerClass = $parsed['handler'];  // 'App\Webhooks\OrderDlrHandler'
    $context = $parsed['context'];        // ['order_id' => 123]

    // Call your handler
    $handler = new $handlerClass();
    $handler->handle($dlr, $context);

    http_response_code(200);
    echo 'OK';

} catch (InvalidSignatureException $e) {
    http_response_code(403);
    echo 'Invalid signature';
}
```

### Callback Data DTOs

**DlrCallbackData** - Delivery receipt information:

```php
$dlr = DlrCallbackData::fromRequest($data);

$dlr->messageId;        // int - The message ID
$dlr->mobile;           // string - Recipient phone number
$dlr->status;           // string - 'delivered', 'failed', 'pending'
$dlr->datetime;         // ?string - Delivery timestamp
$dlr->errorCode;        // ?string - Error code if failed
$dlr->errorDescription; // ?string - Error description if failed

$dlr->isDelivered();    // bool - Check if delivered
$dlr->isFailed();       // bool - Check if failed
$dlr->isPending();      // bool - Check if pending
```

**ReplyCallbackData** - Reply message information:

```php
$reply = ReplyCallbackData::fromRequest($data);

$reply->messageId;      // int - Original message ID
$reply->mobile;         // string - Sender phone number
$reply->message;        // string - Reply message text
$reply->receivedAt;     // string - Timestamp when received
$reply->responseId;     // ?int - Reply ID
$reply->longcode;       // ?string - Number replied to
```

**LinkHitCallbackData** - Link click information:

```php
$linkHit = LinkHitCallbackData::fromRequest($data);

$linkHit->messageId;    // int - Message ID
$linkHit->mobile;       // string - Recipient phone number
$linkHit->url;          // string - URL that was clicked
$linkHit->clickedAt;    // string - Click timestamp
$linkHit->userAgent;    // ?string - Browser user agent
$linkHit->ipAddress;    // ?string - IP address
```

## Laravel Integration

For Laravel projects, use [expertsystemsau/transmitsms-laravel](https://packagist.org/packages/expertsystemsau/transmitsms-laravel) which provides:

- Service provider with automatic configuration
- Facade for convenient access
- Notification channel integration
- **Automatic webhook handling** with job dispatching
- Event-driven callback processing

## License

The MIT License (MIT). Please see [License File](../../LICENSE.md) for more information.
