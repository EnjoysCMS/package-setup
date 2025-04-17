<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;

class Env extends AbstractConfigurator
{

    public function process(): void
    {
        if (!array_key_exists('env', $this->config)) {
            return;
        }
    }
}
