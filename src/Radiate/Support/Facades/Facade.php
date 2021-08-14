<?php

namespace Radiate\Support\Facades;

use RuntimeException;
use Radiate\Foundation\Application;

abstract class Facade
{
    protected static Application $application;

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new RuntimeException('Facade is not implementing getFacadeAccessor method');
    }

    /**
     * Set the application to the
     *
    * @param Application $application
    */
    public static function setFacadeApplication(Application $application): void
    {
        if (! isset(static::$application)) {
            static::$application = $application;
        }
    }

    /**
     * Handling the dynamic and static calls to the object via facades.
     *
     * @param $method
     * @param $arguments
     */
    public static function __callStatic($method, $arguments)
    {
        if (! $instance = static::$application->make(static::getFacadeAccessor()))
            throw new RuntimeException('A Facade has not yet been set.');

        return $instance->$method(...$arguments);
    }
}