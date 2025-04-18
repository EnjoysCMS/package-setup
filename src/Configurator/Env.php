<?php

namespace Enjoyscms\PackageSetup\Configurator;

use Composer\Composer;
use Composer\IO\IOInterface;
use Enjoys\Dotenv\Parser\Env\Value;
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
        $this->io->write(sprintf('<comment>Write ENV: %s</comment>', implode(', ', array_keys($this->options))));
        foreach ($this->options as $key => $value) {
            $dotenvWriter->setEnv($key, new Value($value, '"'),  $this->section ?? '');
        }
        $dotenvWriter->save();
    }
}
