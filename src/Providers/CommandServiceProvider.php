<?php

declare(strict_types=1);

namespace Zahzah\ModuleTransaction\Providers;

use Illuminate\Support\ServiceProvider;
use Zahzah\ModuleTransaction\Commands;

class CommandServiceProvider extends ServiceProvider
{
    private $commands = [
        Commands\InstallMakeCommand::class,
    ];


    public function register(){
        $this->commands(config('module-transaction.commands',$this->commands));
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */

    public function provides()
    {
        return $this->commands;
    }
}
