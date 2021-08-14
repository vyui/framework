<?php

namespace Radiate\Support\Facades;

use Radiate\Support\Providers\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bootstrapFacadeApplication();
    }

    /**
     * Bootstrap the application to the facade, in order for making it easier accessing the applications services via a
     * facade namespace.
     *
     * @return void
     */
    private function bootstrapFacadeApplication(): void
    {
        Facade::setFacadeApplication($this->application);
    }
}