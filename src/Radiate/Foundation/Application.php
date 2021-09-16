<?php

namespace Radiate\Foundation;

use Closure;
use Exception;
use Radiate\Routing\Router;
use Radiate\Foundation\Http\Kernel;
use Radiate\Support\Providers\ServiceProvider;
use Radiate\Contracts\Application\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
{
    /**
     * Version of the Application's Framework.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    protected string $basePath;

    /**
     * Home for all service providers of the application.
     *
     * @var array
     */
    protected array $serviceProviders = [];

    /**
     * Knowledge of which service providers have been instantiated.
     *
     * @var array
     */
    protected array $serviceProvidersRegistered = [];

    /**
     * The locale of the application (This is going to be loaded from core configurations of the application
     *
     * @var string
     */
    protected string $locale = 'en';

    /**
     * Insantiate the Application.
     *
     * @param string|null $basePath
     * @return void
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
     * Return the version of the Application's Framework.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Register the application's core bindings into the application container.
     *
     * @return void
     */
    protected function registerApplicationsBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('application', $this);
        $this->instance('container', $this);
    }

    /**
     * Register all of the Service Providers that the application is needing.
     *
     * @return void
     */
    protected function registerApplicationBaseServiceProviders(): void
    {
        foreach ([
            \Radiate\Support\Environment\EnvironmentServiceProvider::class,
            \Radiate\Support\Config\ConfigServiceProvider::class,
            \Radiate\Exceptions\ExceptionServiceProvider::class,
            \Radiate\Support\Facades\FacadeServiceProvider::class,
            \Radiate\Routing\RoutingServiceProvider::class,
            \Radiate\View\ViewServiceProvider::class
        ] as $serviceProvider) {
            $this->registerServiceProvider($serviceProvider);
        }
    }

    /**
     * Register a Service Provider to the application.
     *
     * @param string|ServiceProvider $provider
     * @return ServiceProvider
     */
    public function registerServiceProvider(string|ServiceProvider $serviceProvider): ServiceProvider
    {
        $registered = is_string($serviceProvider) ? $serviceProvider : get_class($serviceProvider);

        // if the Service Provider had already been registered, then we can simply return the same one... rather than
        // resolving the exact same service provider on a loop.
        if (isset ($this->serviceProviders[$registered])) {
            return $this->serviceProviders[$registed];
        }

        // check whether or not the provider is a string or not; we're going to decide whether or not this already
        // exists.
        $serviceProvider = is_string($serviceProvider) ? new $serviceProvider($this) : $serviceProvider;

        $serviceProvider->register();

        $this->markServiceProviderAsRegistered($registered, $serviceProvider);

        return $serviceProvider;
    }

    /**
     * Forget the ServiceProvider from the application.
     *
     * @param string|ServiceProvider $serviceProvider
     * @return void
     */
    public function forgetServiceProvider(string|ServiceProvider $serviceProvider): void
    {
        $registered = is_string($serviceProvider) ? $serviceProvider : getClass($serviceProvider);

        unset($this->serviceProviders[$registered]);
    }

    /**
     * Mark the particular ServiceProvider as having been registered.
     *
     * @param string $registered
     * @param ServiceProvider $serviceProvider
     * @return void
     */
    protected function markServiceProviderAsRegistered(string $registered, ServiceProvider $serviceProvider): void
    {
        $this->serviceProviders[$registered] = $serviceProvider;
        $this->serviceProvidersRegistered[$registered] = true;
    }

    /**
     * Set the Application's base directory.
     *
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    /**
     * Get the Application's base directory.
     *
     * @param string $path
     * @return string
     */
    public function basePath($path = ''): string
    {
        return $this->basePath . $path;
    }

    /**
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Get the Application's environment, if environments are passed then it will check whether or not
     * the application is in any of the environments passed.
     *
     * @param string|array $environments
     * @return string|bool
     */
    public function environment(...$environments): string|bool
    {
        // placeholder (need to implement getting environments from Environment file. (.env).
        return count($environments) > 0 ? 'dev' : 'live';
    }
}