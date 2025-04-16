<?php

namespace Enjoyscms\PackageSetup\SetupHandlers;

use EnjoysCMS\Core\Console\Utils\CommandsManage;

class ConsoleConfigurator extends SetupHandler
{

    public function process(): void
    {
        if (!array_key_exists('console', $this->config)) {
            return;
        }

        $consoleCommands = $this->config['console'];

        $commandManage = new CommandsManage();
        $registeredCommands = $commandManage->registerCommands($consoleCommands);
        $this->io->write('<comment>Register console commands:</comment>');
        if ($registeredCommands === []) {
            $this->io->write(' <fg=green>- skipped or nothing</></info>');
            return;
        }
        foreach ($registeredCommands as $command) {
            $this->io->write(sprintf(' <fg=green>- %s</></info>', $command));
        }
        $commandManage->save();
    }

}
