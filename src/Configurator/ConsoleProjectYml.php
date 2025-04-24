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
        $commandManage = new ConsoleCommandManager();
        $registeredCommands = $commandManage->registerCommands($this->options);
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
