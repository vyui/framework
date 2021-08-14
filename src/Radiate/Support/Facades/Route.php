<?php

namespace Radiate\Support\Facades;

/**
 * @method static \Radiate\Routing\Router get(string $uri, array|string|callable|array $action = null)
 * @method static \Radiate\Routing\Router post(string $uri, array|string|callable|array $action = null)
 * @method static \Radiate\Routing\Router match(string $uri, array|string|callable|array $action = null)
 *
 * @see \Radiate\Routing\Router
 */
class Route extends Facade
{
    /**
     * Get the facade accessor for the router.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return 'router';
    }   
}