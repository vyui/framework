<?php

namespace Radiate\Support\Config;

use Radiate\Support\Providers\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->application->call(function () {
            $this->registerConfigurations();
        });
    }

    /**
     * Register the applications configurations (found in /config).
     *
     * @return void
     */
    private function registerConfigurations(): void
    {
        $this->application->instance(
            'config',
            new Config($this->application->basePath('/config'))
        );
    }
}