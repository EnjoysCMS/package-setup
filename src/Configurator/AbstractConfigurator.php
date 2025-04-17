<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;

abstract class AbstractConfigurator
{
    protected string $cwd;

    public function __construct(
        protected array $options,
        public readonly Composer $composer,
        public readonly IOInterface $io
    ) {
        $this->cwd = getcwd();
    }

    public function setCwd(string $cwd): void
    {
        $this->cwd = $cwd;
    }

    abstract public function process(): void;


}
