<?php

namespace Radiate\Contracts\Application;

use Radiate\Support\Providers\ServiceProvider;

interface Application
{
    /**
     * Get the version number of the Application.
     *
     * @return string
     */
    public function version(): string;

    /**
     * Get the base path of the Application - usually from where Application was instantiated from.
     *
     * @param string $path
     * @return string
     */
    public function basePath(string $path = ''): string;

    /**
     * Get the Application's environment, if environments are passed then it will check whether or not
     * the application is in any of the environments passed.
     *
     * @param ...$environments
     * @return string|bool
     */
    public function environment(...$environments): string|bool;

    /**
     * Register a ServiceProvider with the Application.
     *
     * @param ServiceProvider $serviceProvider
     * @return ServiceProvider
     */
    public function registerServiceProvider(ServiceProvider $serviceProvider): ServiceProvider;

    /**
     * Forget a ServiceProvider in the Application.
     *
     * @param string|ServiceProvider $serviceProvider
     * @return void
     */
    public function forgetServiceProvider(string|ServiceProvider $serviceProvider): void;

    /**
     * Set the Application's current Locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void;

    /**
     * Get the Application's current Locale.
     *
     * @return string
     */
    public function getLocale(): string;
}