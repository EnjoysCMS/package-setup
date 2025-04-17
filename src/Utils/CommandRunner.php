<?php

namespace Enjoyscms\PackageSetup\Utils;

use Composer\IO\IOInterface;
use Symfony\Component\Process\Process;

class CommandRunner
{
    private \Closure $callback;

    public function __construct(private readonly ?IOInterface $io = null, ?callable $callback = null)
    {
        $this->callback = $callback ?? $this->outputHandler();
    }

    public function execute(
        array $command,
        ?string $cwd = null,
        ?array $env = null,
        mixed $input = null,
        ?float $timeout = 60
    ): void {
        $process = new Process(
            command: $command,
            cwd: $cwd,
            env: $env,
            input: $input,
            timeout: $timeout
        );

        $process->run($this->callback);
    }

    private function outputHandler(): \Closure
    {
        return function ($type, $buffer) {

            if (null === $this->io) {
                echo $buffer;
                return;
            }

            if (Process::ERR === $type) {
                $this->io->writeErrorRaw($buffer, false);
            } else {
                $this->io->writeRaw($buffer, false);
            }
        };
    }

}
