# TransmitSMS Laravel Integration

Laravel notification channel and integration for the [TransmitSMS API](https://transmitsms.com/).

## Installation

```bash
composer require expertsystemsau/transmitsms-laravel-client
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="transmitsms-config"
```

## Configuration

Add your credentials to your `.env` file:

```env
TRANSMITSMS_API_KEY=your-api-key
TRANSMITSMS_API_SECRET=your-api-secret
TRANSMITSMS_FROM=YourSenderID
```

## Usage

### Facade

```php
use ExpertSystems\TransmitSms\Laravel\Facades\TransmitSms;

// Send an SMS
TransmitSms::sendSms('+61400000000', 'Hello from Laravel!');

// Get account balance
$balance = TransmitSms::getBalance();
```

### Notifications

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

## License

The MIT License (MIT). Please see [License File](../../LICENSE.md) for more information.
