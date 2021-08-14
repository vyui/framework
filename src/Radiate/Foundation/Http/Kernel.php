<?php

namespace Radiate\Foundation\Http;

use Throwable;
use Exception;
use Radiate\Http\Request;
use Radiate\Http\Response;
use Radiate\Routing\Router;
use Radiate\Foundation\Application;

class Kernel
{
    /**
     * The Application the kernel is dependent on.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * @var Router
     */
    protected Router $router;

    /**
     * Kernel constructor.
     *
     * @param Application $application
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * The handle method is for the HTTP Kernel to handle the incoming request and handle this in order to form a
     * response to the user; so that we're going to be able to prettily show some results to the client.
     *
     * @param Request $request
     * @throws Exception
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $response = $this->sendRequestThroughRouter($request);

        try {
        } catch (Throwable $exception) {
            $response = $this->renderException($request, $exception);
        }

        return $response;
    }

    /**
     * Send the request through the router and match it to a route that's already in the system.
     *
     * @param Request $request
     * @return Response
     */
    protected function sendRequestThroughRouter(Request $request): Response
    {
        $this->router = $this->application->make('router');

        return $this->router->dispatchRoute($request);
    }

    protected function renderException($request, $exception)
    {
        return new Response('There was a problem');
    }
}