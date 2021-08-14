<?php

namespace Radiate\View;

class View
{
    /**
     * @var Engine
     */
    protected Engine $engine;

    /**
     * @var string
     */
    public string $path;

    /**
     * @var array
     */
    public array $data = [];

    public function __construct(Engine $engine, string $path, array $data = [])
    {
        $this->setEngine($engine)
             ->setPath($path)
             ->setData($data);
    }

    /**
     * @param Engine $engine
     * @return $this
     */
    public function setEngine(Engine $engine): static
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = []): static
    {
        $this->data = $data;

        return $this;
    }

    public function __toString(): string
    {
        return $this->engine->render($this);
    }
}