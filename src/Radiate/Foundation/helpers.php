<?php

use Radiate\Foundation\Container;
use Radiate\Foundation\Application;

if (! function_exists('app')) {
    function app($abstract = null, array $parameters = []): mixed {
        if (is_null($abstract))
            return Application::getInstance();
        return Application::getInstance()->make($abstract, $parameters);
    }
}