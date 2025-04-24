<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Symlink extends AbstractConfigurator
{


    public function process(): void
    {
        $filesystem = new Filesystem();

        foreach ($this->options as $target => $links) {
            $originDir = $this->normalizePath($target);

            try {
                if (!$filesystem->exists($originDir)) {
                    throw new IOException(sprintf('%s not exists.', $originDir));
                }
            } catch (IOException $e) {
                $this->errorIO(
                    sprintf(
                        '<fg=red;bg=default>Target: %s: NOT EXISTS</> <fg=gray;bg=default>%s</>',
                        $originDir,
                        $this->io->isVeryVerbose() ? $e->getMessage() : ''
                    )
                );
                continue;
            }

            foreach ((array)$links ?? [] as $link) {
                try {
                    $targetDir = $this->normalizePath($link);

                    $filesystem->symlink($originDir, $targetDir);
                    $this->io->write(
                        sprintf('<comment>Symlink: %s -> %s:</comment>  <fg=green;bg=default>OK</>', $originDir, $targetDir)
                    );
                } catch (IOException $e) {
                    $this->errorIO(
                        sprintf(
                            '<comment>Symlink: %s -> %s:</comment>  <fg=red;bg=default>NO</> <fg=gray;bg=default>%s</>',
                            $target,
                            $link,
                            $this->io->isVeryVerbose() ? $e->getMessage() : ''
                        )
                    );
                    continue;
                }
            }
        }
    }

    private function errorIO(string $msg): void
    {
        $this->io->write($msg);
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
