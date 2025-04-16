<?php

namespace Enjoyscms\PackageSetup\SetupHandlers;

use EnjoysCMS\Core\Console\Utils\CommandsManage;
use Symfony\Component\Process\Process;

class ConsoleConfigurator extends SetupHandler
{

    public function process(): void
    {
        if (!array_key_exists('console', $this->config)) {
            return;
        }
return;
        $consoleCommands = $this->config['console'];

        $commandManage = new CommandsManage();
        $registeredCommands = $commandManage->registerCommands($this->commands);
        $this->io->write('Register console commands:');
        if ($registeredCommands === []) {
            $this->io->write(' <fg=yellow>- skipped or nothing</></info>');
            return;
        }
        foreach ($registeredCommands as $command) {
            $this->io->write(sprintf(' <fg=yellow>- %s</></info>', $command));
        }
        $commandManage->save();
//        foreach ($consoleCommands as  => $command) {
//            $this->io->write(sprintf('<comment>%s:</comment>', $desc));
//            $process = new Process($command, cwd: $this->cwd);
//            $process->run(function ($type, $buffer) {
//                $this->io->write((Process::ERR === $type) ? 'ERR:' . $buffer : $buffer, false);
//            });
//        }
    }

}
