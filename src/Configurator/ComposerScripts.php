<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\CommandRunner;

class ComposerScripts extends AbstractConfigurator
{

    public function process(): void
    {
        $commandRunner = new CommandRunner($this->io);
        foreach ($this->options as $script) {
            $command = ["composer", "run-script", $script];
            $this->io->write(sprintf('<comment>Call command: %s:</comment>', implode(" ", $command)));
            $commandRunner->execute(command: $command, cwd: $this->cwd);
        }
    }

}
