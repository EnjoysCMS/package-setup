<?php

namespace Enjoyscms\PackageSetup;

class PackageCollection
{
    /**
     * @var Package[]
     */
    private array $collection = [];

    public function add(Package $package): void
    {
        if (!$this->has($package)) {
            $this->collection[$package->getName()] = $package;
        }
    }

    public function has(Package $package): bool
    {
        return array_key_exists($package->getName(), $this->collection);
    }

    /**
     * @return Package[]
     */
    public function getCollection(): array
    {
        return $this->collection;
    }
}
