<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel;

use ExpertSystems\TransmitSms\Laravel\Notifications\TransmitSmsChannel;
use ExpertSystems\TransmitSms\TransmitSmsClient;
use ExpertSystems\TransmitSms\TransmitSmsConnector;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class TransmitSmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/transmitsms.php',
            'transmitsms'
        );

        // Register the connector as a singleton
        $this->app->singleton(TransmitSmsConnector::class, function ($app) {
            /** @var array{api_key: string, api_secret: string, base_url: string, timeout: int} $config */
            $config = $app['config']['transmitsms'];

            return new TransmitSmsConnector(
                apiKey: $config['api_key'],
                apiSecret: $config['api_secret'],
                baseUrl: $config['base_url'],
                timeout: (int) $config['timeout'],
            );
        });

        // Register the client as a singleton, using the connector
        $this->app->singleton(TransmitSmsClient::class, function ($app) {
            return TransmitSmsClient::fromConnector(
                $app->make(TransmitSmsConnector::class)
            );
        });

        // Create aliases for easier resolution
        $this->app->alias(TransmitSmsClient::class, 'transmitsms');
        $this->app->alias(TransmitSmsConnector::class, 'transmitsms.connector');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/transmitsms.php' => config_path('transmitsms.php'),
            ], 'transmitsms-config');
        }

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('transmitsms', function ($app) {
                return $app->make(TransmitSmsChannel::class);
            });
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            TransmitSmsClient::class,
            TransmitSmsConnector::class,
            'transmitsms',
            'transmitsms.connector',
        ];
    }
}
