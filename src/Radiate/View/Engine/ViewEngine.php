<?php

namespace Radiate\View\Engine;

use Radiate\View\View;
use Radiate\View\Manager;

interface ViewEngine
{
    /**
     * @param View $view
     * @return string
     */
    public function render(View $view): string;

    /**
     * @param string|null $content
     * @return string|null
     */
    public function compile(?string $content): ?string;

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setManager(Manager $manager): static;
}