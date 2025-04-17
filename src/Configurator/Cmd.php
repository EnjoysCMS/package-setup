<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Symfony\Component\Process\Process;

class Cmd extends AbstractConfigurator
{

    public function process(): void
    {
        if (!array_key_exists('cmd', $this->config)) {
            return;
        }

        $commands = $this->config['cmd'];
        foreach ($commands as $desc => $command) {
            $this->io->write(sprintf('<comment>%s:</comment>', $desc));
            $process = new Process($command, cwd: $this->cwd);
            $process->run(function ($type, $buffer) {
                $this->io->write((Process::ERR === $type) ? 'ERR:' . $buffer : $buffer, false);
            });
        }
    }

}
