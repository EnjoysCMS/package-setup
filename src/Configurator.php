<?php

namespace Enjoyscms\PackageSetup;

use Enjoyscms\PackageSetup\Configurator\AbstractConfigurator;
use Enjoyscms\PackageSetup\Configurator\Cmd;
use Enjoyscms\PackageSetup\Configurator\ConsoleProjectYml;
use Enjoyscms\PackageSetup\Configurator\Env;
use Enjoyscms\PackageSetup\Configurator\Gitignore;
use Enjoyscms\PackageSetup\Configurator\Symlink;

enum Configurator: string
{
    case Console = 'console';
    case Cmd = 'cmd';
    case Symlink = 'symlink';
    case Env = 'env';
    case Gitignore = 'gitignore';
    case Copy = 'copy';

    /**
     * @return class-string<AbstractConfigurator>|null
     */
    public function handler(): ?string
    {
        return match ($this) {
            self::Console => ConsoleProjectYml::class,
            self::Cmd => Cmd::class,
            self::Symlink => Symlink::class,
            self::Env => Env::class,
            self::Gitignore => Gitignore::class,
            self::Copy => null,
        };
    }
}
