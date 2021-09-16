<?php

namespace Radiate\Routing;

use BadMethodCallException;

abstract class Controller
{
    /**
     * Execute the controller's action.
     *
     * @param string $method
     * @param ...$parameters
     * @return mixed
     */
    public function callAction(string $method, $parameters = []): mixed
    {
        return $this->{$method}(...$parameters);
    }

    /**
     * Handle any calls to missing methods on the target class.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call(string $method, $parameters = []): mixed
    {
        throw new BadMethodCallException(sprintf(
            'Method %s does not exist on %s', $method, static::class
        ));
    }
}