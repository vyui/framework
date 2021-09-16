<?php

namespace Radiate\Http;

class Request
{
    /**
     * @var string|null
     */
    protected ?string $method = null;

    /**
     * @var string|null
     */
    protected ?string $uri = null;

    /**
     * A variety of setup concepts and preparing a variety of things that the request is going to need in order to be
     * interacted with later down the line.
     *
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri    = $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
    
    public function normaliseUri(): string
    {
        return preg_replace(
            '/[\/]{2,}/',
            '/',
            '/' . trim($this->uri, '/') . '/'
        );
    }

    /**
     * This method will essentially create a new request and associate a variety of information regarding the request
     * and store it in this Request object.
     *
     * @return static
     */
    public static function capture(): static
    {
        return new static;
    }
}