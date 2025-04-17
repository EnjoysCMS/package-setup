<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Symlink extends AbstractConfigurator
{


    public function process(): void
    {
        $filesystem = new Filesystem();

        foreach ($this->options as $target => $link) {
            try {
                $originDir = $this->normalizePath($target);
                $targetDir = $this->normalizePath($link);

                if (!$filesystem->exists($originDir)) {
                    throw new IOException(sprintf('%s not exists.', $originDir));
                }
                $filesystem->symlink($originDir, $targetDir);
            } catch (IOException $e) {
                $this->io->write(
                    sprintf(
                        '<comment>Symlink: %s -> %s:</comment>  <fg=red;bg=default>NO</> <fg=gray;bg=default>%s</>',
                        $target,
                        $link,
                        $e->getMessage()
                    )
                );
                continue;
            }
            $this->io->write(
                sprintf('<comment>Symlink: %s -> %s:</comment>  <fg=green;bg=default>OK</>', $target, $link)
            );
        }
    }

    private function normalizePath(string $path): string
    {
        return match (true) {
            \str_starts_with($path, './') => $this->cwd . ltrim($path, '.'),
            \str_starts_with($path, '~/') => $this->cwd . '/' . ltrim($path, '~/'),
            \str_starts_with($path, '/') => $this->composer->getConfig()->get('root-path') . '/' . ltrim($path, '/'),
            default => $this->cwd . '/' . trim($path),
        };
    }
}
