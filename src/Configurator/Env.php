<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Enjoys\Dotenv\Parser\Lines\CommentLine;

class Env extends AbstractConfigurator
{

    /**
     * @throws \Exception
     */
    public function process(): void
    {
        $envPath = getenv('ROOT_PATH').'/.env.dist';
        $dotenvWriter = new \Enjoys\DotenvWriter\DotenvWriter($envPath);
        $this->io->write('<comment>Write ENV:</comment>');
        foreach ($this->options as $key => $value) {
            $this->io->write(sprintf('  - %s=%s', $key, $value));
            $dotenvWriter->addLine(
                new \Enjoys\Dotenv\Parser\Lines\EnvLine(
                    new \Enjoys\Dotenv\Parser\Env\Key($key),
                    new \Enjoys\Dotenv\Parser\Env\Value($value),
                    new \Enjoys\Dotenv\Parser\Env\Comment($this->section ?? '')
                )
            );
        }
        $dotenvWriter->save();
    }
}
