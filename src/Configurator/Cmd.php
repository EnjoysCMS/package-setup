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
            $command = preg_replace_callback('/\$\{(.*)\}/', function ($matches) {
                $value = getenv($matches[1]);
                if ($value === false) {
                    return $matches[0];
                }
                return $value;
            }, $command);
            $commandRunner->execute(command: $command, cwd: $this->cwd);
        }
    }

}
