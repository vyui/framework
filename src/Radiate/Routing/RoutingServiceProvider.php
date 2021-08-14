<?php

namespace Radiate\Routing;

use Radiate\Support\Providers\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    /**
     * The controller's namespace for the application
     *
     * @var string|null
     */
    protected string|null $namespace;

    /**
     * Register the application service
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerRouter();

        $this->application->call(function () {
            foreach (['/routes/web.php', '/routes/api.php'] as $file) {
                if (file_exists($routeFile = base_path($file))) {
                    require_once $routeFile;
                }
            }
        });
    }

    /**
     * Register the applications router; all of the Applications routing principles will be thrown through this
     * particular service.
     *
     * @return void
     */
    protected function registerRouter(): void
    {
        $this->application->instance('router', new Router($this->application));
    }
}