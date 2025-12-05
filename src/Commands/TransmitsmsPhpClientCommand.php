<?php

namespace ExpertSystems\TransmitsmsPhpClient\Commands;

use Illuminate\Console\Command;

class TransmitsmsPhpClientCommand extends Command
{
    public $signature = 'transmitsms-php-client';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
