<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;

class Gitignore extends AbstractConfigurator
{

    public function process(): void
    {
        if (!array_key_exists('gitignore', $this->config)) {
            return;
        }
    }
}
