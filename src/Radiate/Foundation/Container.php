<?php

namespace Radiate\Foundation;

use LogicException;

class Container
{
    use Instanceable;

    /**
     * @var array
     */
    private array $instances;

    /**
     * @var array
     */
    private array $resolved;

    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = [])
    {
        if (! isset($this->instances[$abstract])) {
            throw new LogicException("{$abstract}: has not been defined in instances");
        }

        $this->resolved[$abstract] = true;

        return $this->instances[$abstract];
    }

    /**
     * @param $abstract
     * @param $instance
     * @return mixed
     */
    public function instance($abstract, $instance): mixed
    {
        return ! isset($this->instances[$abstract])
            ? $this->instances[$abstract] = $instance
            : $this->instances[$abstract];
    }

    /**
     * @param $abstract
     * @param $alias
     * @return void
     *
     * @throws LogicException
     */
    public function alias($abstract, $alias): void
    {
        if ($abstract === $alias) {
            throw new LogicException("[$abstract] is aliased to itself");
        }

        $this->aliases[$alias] = $abstract;

        $this->abstractAliases[$abstract][] = $alias;
    }

    /**
     * @param $abstract
     * @param null $concrete
     */
    public function singleton($abstract, $concrete = null): void
    {
        $this->instance($abstract, $concrete);
    }

    /**
     * @param $abstract
     * @param null $concrete
     * @param false $shared
     */
    public function bind($abstract, $concrete = null, $shared = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        if (! $concrete instanceof Closure) {
            if (! is_string($concrete)) {
                throw new Exception(
                    self::class . '::bind(): Argument #2 ($concrete) must be of type Closure|string|null'
                );
            }
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * Call a callable action from the container.
     *
     * @param callable $callback
     * @return mixed
     */
    public function call(callable $callback)
    {
        return $callback($this);
    }
}