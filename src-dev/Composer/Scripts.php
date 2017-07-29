<?php

namespace Sweetchuck\Robo\Yarn\Composer;

use Composer\Script\Event;
use Sweetchuck\GitHooks\Composer\Scripts as GitHooks;

class Scripts
{
    public static function postInstallCmd(Event $event): bool
    {
        GitHooks::deploy($event);

        return true;
    }

    public static function postUpdateCmd(Event $event): bool
    {
        GitHooks::deploy($event);

        return true;
    }
}
