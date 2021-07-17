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
    * @param string $basePath
    */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }
}