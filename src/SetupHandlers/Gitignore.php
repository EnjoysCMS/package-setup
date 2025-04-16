<?php

namespace Enjoyscms\PackageSetup\SetupHandlers;

use Composer\Composer;
use Composer\IO\IOInterface;

class Gitignore extends SetupHandler
{

    public function process(): void
    {
        if (!array_key_exists('gitignore', $this->config)) {
            return;
        }
    }
}
