<?php

declare(strict_types=1);

namespace ExpertSystems\TransmitSms\Laravel;

use ExpertSystems\TransmitSms\Laravel\Notifications\TransmitSmsChannel;
use ExpertSystems\TransmitSms\TransmitSmsClient;
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

        $this->app->singleton(TransmitSmsClient::class, function ($app) {
            /** @var array{api_key: string, api_secret: string} $config */
            $config = $app['config']['transmitsms'];

            return new TransmitSmsClient(
                $config['api_key'] ?? '',
                $config['api_secret'] ?? ''
            );
        });

        $this->app->alias(TransmitSmsClient::class, 'transmitsms');
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
            'transmitsms',
        ];
    }
}
