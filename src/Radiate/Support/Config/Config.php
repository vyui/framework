<?php

namespace Radiate\Support\Config;

use Exception;
use Radiate\Support\Helpers\_Array;

class Config
{
    /**
     * Where the configurations are going to be loaded from.
     *
     * @var string
     */
    protected string $configurationsFilePath;

    /**
     * All application configurations are stored here.
     *
     * @var array
     */
    protected array $configurations = [];

    /**
     * Instantiate the Config.
     *
     * @param string $configurationsFilePath
     * @return void
     */
    public function __construct(string $configurationsFilePath)
    {
        $this->setConfigurationsFilePath($configurationsFilePath)
             ->loadConfigurations();
    }

    /**
     * Set the configurations are going to be loaded from.
     *
     * @param string $configurationsFilePath
     * @return $this
     */
    public function setConfigurationsFilePath(string $configurationsFilePath): static
    {
        $this->configurationsFilePath = $configurationsFilePath;

        return $this;
    }

    /**
     * Reload and apply all the configurations
     *
     * @return $this
     * @throws Exception
     */
    public function reloadConfigurations(): static
    {
        $this->loadConfigurations();

        return $this;
    }

    /**
     * Get an item from the array
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return _Array::find($key, $this->configurations) ?? $default;
    }

    /**
     * Load and apply all of the configurations of the application.
     *
     * @throws Exception
     * @return void
     */
    private function loadConfigurations(): void
    {
        if (! is_dir($this->configurationsFilePath)) {
            throw new Exception("/config directory does not yet exist");
        }

        foreach (scandir($this->configurationsFilePath) as $file) {
            if (mb_strpos($file, '.config.php') === false) {
                continue;
            }

            $this->configurations[
                (explode('.', $file))[0]
            ] = require_once "{$this->configurationsFilePath}/$file";
        }
    }
}