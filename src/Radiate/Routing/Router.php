<?php

namespace Radiate\Routing;

use Closure;
use Exception;
use Radiate\Http\Request;
use Radiate\Http\Response;
use Radiate\Foundation\Application;

class Router
{
    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * @var Application
     */
    protected Application $application;

    /**
     * The current route that has been dispatched.
     *
     * @var Route
     */
    protected ?Route $current;

    /**
     * Router constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function dispatchRoute(Request $request): Response
    {
        try {
            $response = $this->findRoute($request)->dispatchRoute();
        } catch (Exception $exception) {
            
        }

        return $response;
    }

    /**
     * @return Route|null
     * @throws Exception
     */
    private function findRoute(Request $request): ?Route
    {
        if (empty($this->routes[$request->getMethod()])) {
            throw new Exception("No Routes found for: {$request->getMethod()}");
        }

        foreach ($this->routes[$request->getMethod()] as $route) {
            if ($route->matchRequest($request)) {
                return $this->setCurrent($route)->current;
            }
        }

        throw new Exception("No Route Found");
    }

    /**
     * @param string $uri
     * @param string|array|Closure|null $action
     * @return void
     */
    public function get(string $uri, string|array|Closure $action = null): void
    {
        $this->routes['GET'][] = new Route('GET', $uri, $action);
    }

    /**
     * @param string $uri
     * @param string|array|Closure|null $action
     * @return void
     */
    public function post(string $uri, string|array|Closure $action = null): void
    {
        $this->routes['POST'][] = new Route('POST', $uri, $action);
    }

    /**
     * @param string $uri
     * @param string|array|Closure|null $action
     * @return void
     */
    public function match(string $uri, string|array|Closure $action = null): void
    {
        foreach (['POST', 'GET'] as $method) {
            $this->routes[$method][] = new Route($method, $uri, $action);
        }
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setCurrent(Route $route): static
    {
        $this->current = $route;

        return $this;
    }
}