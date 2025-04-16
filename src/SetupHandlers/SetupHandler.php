<?php

namespace Enjoyscms\PackageSetup\SetupHandlers;

use Composer\Composer;
use Composer\IO\IOInterface;

abstract class SetupHandler
{
    protected string $cwd;

    public function __construct(
        protected array $config,
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
