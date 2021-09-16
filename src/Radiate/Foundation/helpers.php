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
    /**
     * @param string $path
     * @return string
     */
    function base_path(string $path = ''): string {
        return app()->basePath($path);
    }
}

if (! function_exists('dd')) {
    /**
     * @param ...$params
     */
    function dd(...$params): void {
        echo '<pre>';
            var_dump($params);
        echo '</pre>';
        die();
    }
}

if (! function_exists('view')) {
    /**
     * @param string $template
     * @param array $data
     * @return \Radiate\View\View
     */
    function view(string $template, array $data = []) : \Radiate\View\View {
        static $manager;
    }
}

if (! function_exists('config')) {
    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    function config(string $key, mixed $default = null): mixed {
        return app()->make('config')->get($key, $default);
    }
}