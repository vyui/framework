<?php

namespace Radiate\Support\Providers;

use Radiate\Foundation\Application;

abstract class ServiceProvider
{
    /**
     * @var Application
     */
    protected Application $application;

    /**
     * ServiceProvider constructor.
     *
     * @param Application $application
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Register any application's needed services.
     *
     * @return void
     */
    public function register(): void
    {
        // register the service provider
    }
}