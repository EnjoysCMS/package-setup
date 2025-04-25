<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\GitignoreManage;

class Gitignore extends AbstractConfigurator
{

    public function process(): void
    {
        $gitignoreManage = new GitignoreManage(
            gitignoreFile: $this->composer->getConfig()->get('root-path') . '/.gitignore'
        );

        $this->io->write('<comment>Writing to .gitignore:</comment>');
        foreach ($this->options as $value) {
            $gitignoreManage->add($value);
        }
        $gitignoreManage->save();
    }
}
