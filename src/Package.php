<?php

namespace Enjoyscms\PackageSetup;

use Composer\Package\PackageInterface;
use Composer\Package\RootPackage;

readonly class Package
{
    public function __construct(
        public PackageInterface $info,
        public string $installationPath
    ) {
    }

    public function getName(): string
    {
        return ($this->info instanceof RootPackage) ? 'Application' : $this->info->getName();
    }

    public function getPrettyName(): string
    {
        return $this->info->getPrettyName();
    }
}
