<?php

namespace Radiate\View\ViewEngine;

use Radiate\View\View;

class BladeEngine implements ViewEngine
{
    /**
     * @var Manager
     */
    protected Manager $manager;

    /**
     * @var array
     */
    protected array $layouts = [];

    /**
     * @param View $view
     * @return string
     */
    public function render(View $view): string
    {
        $hash = md5($view->path);

        $folder = base_path('storage/framework/views');

        $cached = "{$folder}/{$hash}.php";

        if (! file_exists($cached) || filemtime($view->path) > filemtime($cached)) {
            file_put_contents($cached, $this->compile(file_get_contents($view->path)));
        }

        extract($this->data);

        ob_start();

        include $cached;

        $contents = ob_get_contents();

        ob_end_clean();

        if ($layout = $this->layouts[$cached] ?? null) {
            return new View ($layout, array_merge($view->data, ['contents' => $contents]));
        }

        return $contents;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function compile(string $template): string
    {
        // replacing @extends(string '') for $this->extends(string '');
        $template = preg_replace_callback('#@extends\(([^)]+)\)#', function ($matches): string {
            return '<?php $this->extends(' . $matches[1] . '); ?>';
        }, $template);

        // replacing @if(condition...) for if (condition):
        $template = preg_replace_callback('#@if\(([^)]+)\)#', function ($matches): string {
            return '<?php if(' . $matches[1] . '): ?>';
        }, $template);

        // replacing @endif for endif;
        $template = preg_replace_callback('#@endif#', function (): string {
            return '<?php endif; ?>';
        }, $template);

        $template = preg_replace_callback('#@elseif\(([^)]+)\)#', function ($matches): string {
            return '<?php elseif(' . $matches[1] . '): ?>';
        }, $template);

        $template = preg_replace_callback('#@else#', function (): string {
            return '<?php else: ?>';
        }, $template);

        $template = preg_replace_callback('#\{\{([^}]+)\}\}#', function ($matches): string {
            return '<?php print $this->escape(' . $matches[1] . '); ?>';
        }, $template);

        $template = preg_replace_callback('#\{!!([^}]+)!!\}#', function ($matches): string {
            return '<?php print ' . $matches[1] . '; ?>';
        }, $template);

        $template = preg_replace_callback('#@([^(]+)\(([^)]+)\)#', function ($matches): string {
            return '<?php $this->' . $matches[1] . ' (' . $matches[2] . '); ?>';
        }, $template);

        return $template;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function extends(string $template): static
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $this->layouts[realpath($backtrace[0]['file'])] = $template;
        return $this;
    }

    /**
     * @param string $template
     * @param array $data
     */
    public function include(string $template, array $data = []): void
    {
        print view($template, $data);
    }

    /**
     * @param Manager $manager
     * @return $this
     */
    public function setManager(Manager $manager): static
    {
        $this->manager = $manager;

        return $this;
    }
}