<?php

namespace Radiate\Foundation;

trait Instanceable
{
    protected static $instance;

    /**
    * @param mixed $instance
     * @return void
    */
    public static function setInstance(mixed $instance): void
    {
        static::$instance = $instance;
    }

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return ! is_null(static::$instance)
            ? static::$instance
            : new static;
    }
}