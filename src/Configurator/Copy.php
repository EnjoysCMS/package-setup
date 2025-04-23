<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Copy extends AbstractConfigurator
{


    public function process(): void
    {
        $filesystem = new Filesystem();

        foreach ($this->options as $from => $destination) {
            $originFile = $this->normalizePath($from);

            try {
                if (!$filesystem->exists($originFile)) {
                    throw new IOException(sprintf('%s not exists.', $originFile));
                }
            } catch (IOException $e) {
                $this->errorIO($e);
                continue;
            }

            foreach ((array)$destination ?? [] as $to) {
                $targetFile = $this->normalizePath($to);
                try {
                    $filesystem->copy($originFile, $targetFile);
                    $this->io->write(
                        sprintf(
                            '<comment>Copy: %s -> %s:</comment>  <fg=green;bg=default>OK</>',
                            $originFile,
                            $targetFile
                        )
                    );
                } catch (IOException $e) {
                    $this->errorIO($e);
                    continue;
                }
            }
        }
    }

    private function errorIO(\Exception $e): void
    {
        $this->io->write(
            sprintf(
                '<comment>Copy:</comment> <fg=red;bg=default>%s</>',
                $e->getMessage()
            )
        );
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
