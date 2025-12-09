# TransmitSMS PHP Client

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

## Laravel Integration

For Laravel projects, use [expertsystemsau/transmitsms-laravel-client](https://packagist.org/packages/expertsystemsau/transmitsms-laravel-client) which provides a service provider, facade, and notification channel.

## License

The MIT License (MIT). Please see [License File](../../LICENSE.md) for more information.
