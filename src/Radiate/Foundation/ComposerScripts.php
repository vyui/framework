<?php

namespace Radiate\Foundation;

use Composer\Script\Event;
use Radiate\Foundation\Application;

class ComposerScripts
{
    /**
     * Handle the post-install Composer event.
     *
     * @param Event $event
     * @return void
     */
    public static function postInstall(Event $event): void
    {
        static::clearCompiled();
    }

    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param Event $event
     * @return void
     */
    public static function postAutoloadDump(Event $event): void
    {
        static::clearCompiled();
    }

    /**
     * Clear the cached application bootstrapping files.
     *
     * @return void
     */
    public static function clearCompiled()
    {
        $vyui = new Application(getcwd());
    }
}