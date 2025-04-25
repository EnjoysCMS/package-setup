<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\GitignoreManage;

class Gitignore extends AbstractConfigurator
{

    public function process(): void
    {
        $this->io->write('<comment>Writing to .gitignore:</comment>');
        try {
            $gitignoreManage = new GitignoreManage(
                gitignoreFile: $this->composer->getConfig()->get('root-path') . '/.gitignore'
            );
            foreach ($this->options as $value) {
                $gitignoreManage->add($value);
                $this->io->write(sprintf('<fg=green> - added "%s"</>', $value));
            }
            $gitignoreManage->save();
        } catch (\Exception $e) {
            $this->io->write(
                sprintf(
                    '<fg=red;bg=default>%s</>',
                    $e->getMessage()
                )
            );
        }
    }
}
