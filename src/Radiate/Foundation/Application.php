<?php

namespace Radiate\Foundation;

use Closure;
use Exception;
use Radiate\Routing\Router;
use Radiate\Foundation\Http\Kernel;
use Radiate\Support\Providers\ServiceProvider;

class Application extends Container
{
    /**
     * @var string
     */
    public string $basePath;

    /**
     * @var array
     */
    protected array $serviceProviders = [];

    /**
     * Application constructor.
     * @param string|null $basePath
     */
    public function __construct(string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerApplicationsBaseBindings();
        $this->registerApplicationBaseServiceProviders();
    }

    /**
     * Register the application's core bindings into the application container.
     *
     * @return void
     */
    protected function registerApplicationsBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('kernel', new Kernel($this));
    }

    /**
     * @return void
     */
    protected function registerApplicationBaseServiceProviders(): void
    {
        $this->registerServiceProvider(\Radiate\Support\Facades\FacadeServiceProvider::class);
        $this->registerServiceProvider(\Radiate\Routing\RoutingServiceProvider::class);
        $this->registerServiceProvider(\Radiate\View\ViewServiceProvider::class);
    }

    /**
     * @param \Radiate\Foundation\Providers\ServiceProvider $provider
     */
    public function registerServiceProvider($provider)
    {
        // if the Service Provider had already been registered, then we can simply return the same one... rather than
        // resolving the exact same service provider on a loop.
        if (isset ($this->serviceProviders[($registered = is_string($provider) ? $provider : get_class($provider))])) {
            return $this->serviceProviders[$registed];
        }

        // check whether or not the provider is a string or not; we're going to decide whether or not this already
        // exists.
        $provider = is_string($provider) ? new $provider($this) : $provider;

        $this->serviceProviders[$registered] = $provider;

        $provider->register();

        return $provider;
    }

    /**
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * @param string $path
     * @return string
     */
    public function basePath($path = ''): string
    {
        return $this->basePath . $path;
    }
}