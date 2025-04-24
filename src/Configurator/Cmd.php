<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\CommandRunner;

class Cmd extends AbstractConfigurator
{

    public function process(): void
    {
        $commandRunner = new CommandRunner($this->io);

        foreach ($this->options as $desc => $command) {
            $this->io->write(sprintf('<comment>%s:</comment>', $desc));
            $commandRunner->execute(command: $command, cwd: $this->cwd);
        }
    }

}
