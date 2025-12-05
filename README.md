# TransmitSMS PHP Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/expertsystemsau/transmitsms-client.svg?style=flat-square)](https://packagist.org/packages/expertsystemsau/transmitsms-client)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/expertsystemsau/transmitsms-php-client/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/expertsystemsau/transmitsms-php-client/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/expertsystemsau/transmitsms-client.svg?style=flat-square)](https://packagist.org/packages/expertsystemsau/transmitsms-client)

A PHP client for the [TransmitSMS API](https://transmitsms.com/). This monorepo contains two packages:

- **`expertsystemsau/transmitsms-client`** - Framework-agnostic PHP client
- **`expertsystemsau/transmitsms-laravel`** - Laravel notification channel integration

## Installation

### For Plain PHP Projects

Install the core client package:

```bash
composer require expertsystemsau/transmitsms-client
```

### For Laravel Projects

Install the Laravel integration package (includes the core client):

```bash
composer require expertsystemsau/transmitsms-laravel
```

Then publish the configuration file:

```bash
php artisan vendor:publish --tag="transmitsms-config"
```

## Configuration

### Plain PHP

```php
use ExpertSystems\TransmitSms\TransmitSmsClient;

$client = new TransmitSmsClient(
    apiKey: 'your-api-key',
    apiSecret: 'your-api-secret'
);
```

### Laravel

Add your credentials to your `.env` file:

```env
TRANSMITSMS_API_KEY=your-api-key
TRANSMITSMS_API_SECRET=your-api-secret
TRANSMITSMS_FROM=YourSenderID
```

## Usage

### Core Client (Plain PHP)

```php
use ExpertSystems\TransmitSms\TransmitSmsClient;

$client = new TransmitSmsClient('api-key', 'api-secret');

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
```

### Laravel Facade

```php
use ExpertSystems\TransmitSms\Laravel\Facades\TransmitSms;

// Send an SMS
TransmitSms::sendSms('+61400000000', 'Hello from Laravel!');

// Get account balance
$balance = TransmitSms::getBalance();
```

### Laravel Notifications

Create a notification that uses the TransmitSMS channel:

```php
use Illuminate\Notifications\Notification;
use ExpertSystems\TransmitSms\Laravel\Notifications\TransmitSmsMessage;

class OrderShipped extends Notification
{
    public function via($notifiable): array
    {
        return ['transmitsms'];
    }

    public function toTransmitSms($notifiable): TransmitSmsMessage
    {
        return (new TransmitSmsMessage())
            ->content('Your order has been shipped!')
            ->from('MyStore');
    }
}
```

Add the `routeNotificationForTransmitsms` method to your notifiable model:

```php
class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForTransmitsms($notification): ?string
    {
        return $this->phone_number;
    }
}
```

Then send notifications:

```php
$user->notify(new OrderShipped());
```

## Package Structure

```
packages/
├── transmitsms-client/     # Core PHP client (no framework dependencies)
│   └── src/
│       ├── TransmitSmsClient.php
│       └── Exceptions/
│           └── TransmitSmsException.php
│
└── transmitsms-laravel/    # Laravel integration
    ├── src/
    │   ├── TransmitSmsServiceProvider.php
    │   ├── Facades/
    │   │   └── TransmitSms.php
    │   └── Notifications/
    │       ├── TransmitSmsChannel.php
    │       └── TransmitSmsMessage.php
    └── config/
        └── transmitsms.php
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mitchell Williams](https://github.com/mitchello77)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
