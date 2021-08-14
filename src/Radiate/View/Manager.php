<?php

namespace Radiate\View;

use Closure;
use Exception;
use Radiate\View\Engine\Engine;
use Radiate\Foundation\Application;

class Manager
{
    /**
     * @var array
     */
    protected array $paths = [];

    /**
     * @var array
     */
    protected array $engines = [];

    /**
     * @var array
     */
    protected array $macros = [];

    /**
     * @param string $path
     * @return $this
     */
    public function addPath(string $path): static
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * @param string $extension
     * @param Engine $engine
     * @return $this
     */
    public function addEngine(string $extension, Engine $engine): static
    {
        ($this->engines[$extension] = $engine)->setManager($this);

        return $this;
    }

    /**
     * @param string $name
     * @param Closure $closure
     * @return $this
     */
    public function addMacro(string $name, Closure $closure): static
    {
        $this->macros[$name] = $closure;

        return $this;
    }

    /**
     * @param string $name
     * @param ...$values
     * @return mixed
     * @throws Exception
     */
    public function useMacro(string $name, ...$values): mixed
    {
        if (isset($this->macros[$name])) {
            return ($this->macros[$name]->bindTo($this))(...$values);
        }

        throw new Exception("The target macro has not been defined: {$name}");
    }

    /**
     * @param string $template
     * @param array $data
     * @return View
     * @throws Exception
     */
    public function resolve(string $template, array $data = []): View
    {
        foreach ($this->engines as $extension => $engine) {
            foreach ($this->paths as $path) {
                if (is_file($file = base_path("{$path}/{$template}.$extension"))) {
                    return new View($engine, $file, $data);
                }
            }
        }

        throw new Exception("Unable to render: {$template}");
    }
}