<?php

namespace Radiate\Foundation;

class Application extends Container
{
    /**
     * @var string
     */
    public string $basePath;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        self::setInstance($this);
    }

    /**
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;
        return $this;
    }
}