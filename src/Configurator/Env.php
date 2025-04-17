<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;

class Env extends AbstractConfigurator
{

    /**
     * @throws \Exception
     */
    public function process(): void
    {
        $envPath = getenv('ROOT_PATH').'/.env.dist';
        $dotenvWriter = new \Enjoys\DotenvWriter\DotenvWriter($envPath);
        $dotenvWriter->addLine(
            new \Enjoys\Dotenv\Parser\Lines\CommentLine('sdfghj')
        );
        foreach ($this->options as $key => $value) {
            $this->io->write(sprintf('<comment>Write ENV: %s</comment>', $key));
            $dotenvWriter->addLine(
                new \Enjoys\Dotenv\Parser\Lines\EnvLine(
                    new \Enjoys\Dotenv\Parser\Env\Key($key),
                    new \Enjoys\Dotenv\Parser\Env\Value($value)
                )
            );
        }
        $dotenvWriter->save();
    }
}
