<?php

namespace Enjoyscms\PackageSetup\SetupHandlers;

use Composer\Composer;
use Composer\IO\IOInterface;

class Env extends SetupHandler
{

    public function process(): void
    {
        if (!array_key_exists('env', $this->config)) {
            return;
        }
    }
}
