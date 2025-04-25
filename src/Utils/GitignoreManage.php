<?php

namespace Enjoyscms\PackageSetup\Utils;

use function Enjoys\FileSystem\writeFile;

class GitignoreManage
{

    private array $structure;

    /**
     * @throws \Exception
     */
    public function __construct(private string $gitignoreFile)
    {
        if (!file_exists($gitignoreFile)) {
            writeFile($gitignoreFile, '', 'w+');
        }

        $this->structure = $this->parseStructure(file_get_contents($gitignoreFile) ?: '');
    }

    public function add(string $value): GitignoreManage
    {
        if (preg_match("/\R/u", $value) !== 0) {
            throw new \InvalidArgumentException('The input string should not consist of several lines.');
        }

        if (!in_array($value, $this->structure, true)) {
            $this->structure[] = $value;
        }

        return $this;
    }

    public function save(): void
    {
        file_put_contents($this->gitignoreFile, implode("\n", $this->structure));
    }

    private function parseStructure(string $content): array
    {
        $split = preg_split("/\R/u", $content);
        return ($split === false) ? [] : $split;
    }
}
