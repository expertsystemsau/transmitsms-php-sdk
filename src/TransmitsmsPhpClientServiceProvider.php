<?php

namespace ExpertSystems\TransmitsmsPhpClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ExpertSystems\TransmitsmsPhpClient\Commands\TransmitsmsPhpClientCommand;

class TransmitsmsPhpClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('transmitsms-php-client')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_transmitsms_php_client_table')
            ->hasCommand(TransmitsmsPhpClientCommand::class);
    }
}
