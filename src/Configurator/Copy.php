<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Enjoyscms\PackageSetup\Utils\PathUtils;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class Copy extends AbstractConfigurator
{


    public function process(): void
    {
        $filesystem = new Filesystem();

        foreach ($this->options as $from => $destination) {
            $originFile = PathUtils::normalizePath($from, $this->composer->getConfig()->get('root-path'), $this->cwd);


            try {
                if (!$filesystem->exists($originFile)) {
                    throw new IOException(sprintf('%s not exists.', $originFile));
                }
            } catch (IOException $e) {
                $this->errorIO($e);
                continue;
            }

            foreach ((array)$destination ?? [] as $to) {
                $targetFile = PathUtils::normalizePath($to,  $this->composer->getConfig()->get('root-path'), $this->cwd);
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


}
