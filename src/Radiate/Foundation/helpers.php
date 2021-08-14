<?php

use Radiate\Foundation\Container;
use Radiate\Foundation\Application;

if (! function_exists('app')) {
    /**
     * Get the container instance, or get an abstract from the container.
     *
     * @param string|null $abstract
     * @return mixed|Application
     */
    function app(?string $abstract = null): mixed {
        return ! is_null($abstract)
            ? Container::getInstance()->make($abstract)
            : Container::getInstance();
    }
}

if (! function_exists('base_path')) {
    function base_path(string $path = ''): string {
        return app()->basePath($path);
    }
}

if (! function_exists('dd')) {
    function dd(...$params): void {
        var_dump($params);
        die();
    }
}

if (! function_exists('view')) {
    function view(string $template, array $data = []) : \Radiate\View\View {
        static $manager;
    }
}