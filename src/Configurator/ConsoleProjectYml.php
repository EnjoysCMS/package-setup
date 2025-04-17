<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\ConsoleCommandManager;

class ConsoleProjectYml extends AbstractConfigurator
{

    /**
     * @throws \Exception
     */
    public function process(): void
    {
        if (!array_key_exists('console', $this->config)) {
            return;
        }

        $consoleCommands = $this->config['console'];

        $commandManage = new ConsoleCommandManager();
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
