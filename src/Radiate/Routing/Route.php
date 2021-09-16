<?php

namespace Radiate\Routing;

use Closure;
use Radiate\Http\Request;
use Radiate\Http\Response;

class Route
{
    /**
     * @var string
     */
    protected string $method;

    /**
     * @var string
     */
    protected string $uri;

    /**
     * Upon a Uri being defined, we are going to define it's regex finder, in order for matching a route to a request.
     *
     * @var string
     */
    protected string $uriRegex;

    /**
     * @var string|Closure
     */
    protected string|array|Closure $handler;

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * Route constructor.
     *
     * @param string $method
     * @param string $uri
     * @param string|array|Closure $handler
     * @return void
     */
    public function __construct(string $method, string $uri, string|array|Closure $handler)
    {
        $this->setMethod($method)
             ->setUri($uri)
             ->setHandler($handler);
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): static
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set the uri of the route; this will be utilised for matching the uri to the request uri and if they match we are
     * going to serve a particular method depending on what's matching. Upon setting the uri, if there are {} slugs
     * in place, then we're going to set a regex matcher based on the uri that has been tapped into and then begin
     * preparing the route prior to it being rendered.
     *
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri): static
    {
        $this->uri = $uri;

        return $this->setUriRegex($uri);
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * A Method of which translates the Route uri into something that's more matchable to something more dynamic from
     * the request uri of the server.
     *
     * @param string $regex
     * @return $this
     */
    public function setUriRegex(string $uri): static
    {
        $this->uriRegex = preg_replace_callback('#{([^}]+)}/#', function (array $found): string {
            $this->parameters[] = rtrim($found[1], '?');
            return str_ends_with($found[1], '?')
                ? '([^/]*)(?:/?)'
                : '([^/]+)/';
        }, $this->normaliseUri());

        return $this;
    }

    /**
     * @return string
     */
    public function getUriRegex(): string
    {
        return $this->uriRegex;
    }

    /**
     * Here we are going to process what the route's callback will be, if it has been defined as an array, then we are
     * going to build the handler in a particular way, if it's a string, then we're going to instantiate everything in
     * a way that's needed and if it's already a closure, simply return the closure.
     *
     * @param string|array|Closure $handler
     * @return $this
     */
    public function setHandler(string|array|Closure $handler): static
    {
        if ($handler instanceof Closure) {
            $this->handler = $handler;
        }

        if (is_array($handler) || is_string($handler)) {
            [$controller, $method] = is_array($handler) ? $handler : explode('@', $handler);
            $this->handler = function () use ($controller, $method) {
                return (new ($controller))->{$method}();
            };
        }

        return $this;
    }

    /**
     * @return string
     */
    private function normaliseUri(): string
    {
        return preg_replace(
            '/[\/]{2,}/',
            '/',
            '/' . trim($this->uri, '/') . '/'
        );
    }

    private function extractRequestValues(Request $request, $key = 0): bool
    {
        preg_match_all("#{$this->uriRegex}#", $request->normaliseUri(), $matches);

        if (empty(array_filter($matches, fn($match): bool => ! empty($match)))) {
            return false;
        }

        foreach ($matches as $match) {
            if (! empty($match[0]) && $match[0] !== $request->normaliseUri()) {
                $this->parameters[$this->parameters[$key]] = $match[0] !== '' ? $match[0] : null;
                unset($this->parameters[$key]);
                $key ++;
            }
        }

        return true;
    }

    /**
     * Check whether or not this request is possible to be matched against the request.
     *
     * @param Request $request
     * @return bool
     */
    public function matchRequest(Request $request): bool
    {
        // if we have a direct match, then we can just simply return this specific route... and no need to continue
        // on with the rest of the regex matching.
        if ($this->method === $request->getMethod() && $this->uri === $request->getUri()) {
            return true;
        }

        if (! str_contains($this->uriRegex, '+') && ! str_contains($this->uriRegex, '*')) {
            return false;
        }

        if ($this->extractRequestValues($request)) {
            return true;
        }

        return false;
    }

    public function dispatchRoute(): Response
    {
        return new Response(($this->handler)());
    }
}